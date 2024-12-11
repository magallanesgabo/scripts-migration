<?php
/* function find_wordpress_base_path() {
    $dir = dirname(__FILE__);
    do {
        if( file_exists($dir."/wp-config.php") ) {
            return $dir;
        }
    } while( $dir = realpath("$dir/..") );
    return null;
}

define( 'BASE_PATH', find_wordpress_base_path()."/" );
define( 'WP_USE_THEMES', false );

global $wpdb;
require(BASE_PATH . 'wp-load.php');

function insertContactoFromMailchimp($obj) {
    $firstName = $this->getDataMailchimp($obj)['merge_fields']['FNAME'];
    $lastName = $this->getDataMailchimp($obj)['merge_fields']['LNAME'];
    // Get Permalink contacto
    $this->load->model('programas_model');
    $nombres = $firstName.' '.$lastName;
    $permalink = $this->programas_model->getIndividualPermalink($nombres,"contacto");

    $this->db->insert('TContact',array('FirstName' => @$firstName,'LastName'=> @$lastName,'Email' => $obj->email,'Activo'=>1,'ordenTitOTT'=> 0,'contactFromSuscri'=>0,'Metadata'=>'[46]','Actualizado' => date('Y-m-d'),'permalink' => $permalink));
    return $this->db->insert_id();
}

function checkExistContact($idcontacto, $lista) {
    $sql="SELECT count(*) as total FROM  $this->tablename_c where IdContact= ".$idcontacto." and  IdLista = '".$lista."' ";
    $query = $this->db->query( $sql );
    $result = $query->row();
    return $result;
}

function addMetaContact($idcontacto) {
    $this->load->library('MailChimp');
    $this->load->model('contactos_model');
    $meta = $this->contactos_model->getMetaContactosByEmail($idcontacto);
    if (count($meta) > 0) {
        $getMeta = json_decode($meta[0]->Metadata);
        $dataMeta = [];
        foreach($getMeta as $valor){
            $name = $this->contactos_model->getNameMetadata($valor);
            array_push($dataMeta,array('name' => $name->Name,'status' => 'active')) ;
        }

        foreach($meta as $item) {
            $email_md5=$this->mailchimp->subscriberHash($item->Email);
            $this->mailchimp->post('lists/'.$item->IdLista.'/members/'.$email_md5.'/tags',['tags'=>$dataMeta]);
        }
    }
}

function saveDatafromWebhooks($action,$status,$idcontacto,$obj) {
    $this->load->library('MailChimp');
    $interests = null;
    $lista_id = $obj->list_id;

    if (!empty($obj->interests)) {
        $interests = $this->getGruposMailchimp($obj);
    }

    if ($action == 'update') {
        $this->db->update($this->tablename_c,array('Status'=>$status,'Interests'=>$interests),array('IdContact' =>$idcontacto,'IdLista' =>$lista_id)) ;
    }

    if ($action == 'insert') {
        $this->db->insert($this->tablename_c,array('IdContact' =>$idcontacto,'IdLista' =>$lista_id,'Interests' =>$interests,'Status'=>$status));
    }

    if ($lista_id == 'f1d79e81ff') {
        $addMember = new StdClass();
        $addMember->status = $status;
        $lista_incluir = '9751abefba';
        $email_md5 = $this->mailchimp->subscriberHash($obj->email);
        $isSubscribed= $this->mailchimp->get('lists/'.$lista_incluir.'/members/'.$email_md5);
        $aux = '';
        $method = '';
        if($obj->type == 'subscribe'){
            $method = (isset($isSubscribed['id'])) ? 'patch' : 'post';
            $addMember->interests = array('69e3e1bd10' => true);
            $aux = ($method == 'patch') ? $email_md5 : '';

        }elseif($obj->type == 'unsubscribe' || $obj->type == 'cleaned'){
            $addMember->interests = array('69e3e1bd10' => false);
            unset($addMember->status);
            $method = 'patch';
            $aux = $email_md5;
        }

        if(!empty($method))
        $this->mailchimp->{$method}('lists/'.$lista_incluir.'/members/'.$aux,$addMember);

    }

    //Asociar metadatas a contacto
    if($obj->type == 'subscribe'){
        $this->addMetaContact($idcontacto);
    }
}

function getGruposMailchimp($obj) {
    $aux=[];
    $data=$this->getDataMailchimp($obj);
    if(count($data['interests']) > 0 ) {
        foreach($data['interests'] as $key=>$value){
                if($value){
                    $aux[] = $key;
                }
        }
    }
    $interests = json_encode($aux);
    return $interests;
}


function checkExistContactoWebhook($email) {
    $sql = "SELECT IdContactFM,Metadata,Depto2,Title,Activo as Cargo,comments FROM TContact WHERE Email = '".$email."'";
    $query = $this->db->query( $sql );
    $result = $query->result();

    if(count($result) > 0){
        $data = array_filter($result,function() {
            return $result->Activo == 1;
        });
        if(!empty($data)) {
            return $data[0];
        }else{
            return $result[0];
        }
    }
    return $result;
}

#Vaida si contacto existe en local, actualiza o crea contacto
function validate_contact_from_mailchimp($status, $data) {
    $result = $this->checkExistContactoWebhook($obj->email);
    // Se verifica que exista el contacto y esa lista en TContactoMailchimp
    if(is_numeric($result->IdContactFM)) {
        $checkContacto =$this->checkExistContact($result->IdContactFM,$obj->list_id);
        if( $checkContacto->total > 0) {
            $this->saveDatafromWebhooks('update',$status,$result->IdContactFM,$obj);
        }else{
            $this->saveDatafromWebhooks('insert',$status,$result->IdContactFM,$obj);
        }

    }else{
        $contactoId = $this->insertContactoFromMailchimp($obj);
        $this->saveDatafromWebhooks('insert',$status,$contactoId,$obj);

    }
    return true;

}

#Proceso principal de actualización de un contacto
function update_contact($data) {
    $MailChimp = new \DrewM\MailChimp\MailChimp(MAILCHIMP_API_KEY);

    if (is_array($data)) {
        $contact_status = '';
        switch ($data['type']) {
            case 'subscribe':
                $contact_status = 'subscribed';
                validate_contact_from_mailchimp($contact_status, $data);
                break;
            case 'unsubscribe':
                $contact_status = 'unsubscribed';
                validate_contact_from_mailchimp($contact_status, $data);
                break;
            case 'cleaned':
                $contact_status = 'cleaned';
                validate_contact_from_mailchimp($contact_status, $data);
                break;
            case 'profile':
                $contact_data = $MailChimp->get('/lists/'.$data['list_id'].'/members/'.$data['email'].'?fields=interests,status,merge_fields');
                $contact_status =  $contact_data['status'];
                validate_contact_from_mailchimp($contact_status, $data);
                break;
            case 'upemail':
                if (!empty($data['email_old']) && !empty($data['email_new'])) {
                    #Actualizar info local
                    updateEmailContacto($data['email_old'], $data['email_new']);
                }
                break;
        }
    }
}

#Init
if ($_POST) {
    $data_from_mailchimp = array(
        'type'      => $_POST['type'],
        'list_id'   => $_POST['data']['list_id'],
        'email'     => $_POST['data']['email'],
        'interests' => $_POST['data']['merges']['INTERESTS'],
    );

    if ($data_from_mailchimp['type'] === 'upemail') {
        $data_from_mailchimp['email_old'] = $_POST['data']['old_email'];
        $data_from_mailchimp['email_new'] = $_POST['data']['new_email'];
    }

    update_contact($data_from_mailchimp);
} */