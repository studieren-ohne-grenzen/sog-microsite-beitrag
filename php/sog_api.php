<?php
class SogApi {
    private $api;
    private $debug;
    private $defaults = array('sequential' => true);

    function __construct($debug = 0) {
        require_once($_SERVER['DOCUMENT_ROOT'] . 'civi4/sites/all/modules/civicrm/api/class.api.php');

        $this->debug = $debug;

        // the path to civicrm.settings.php
        $this->api = new civicrm_api3(array(
            'conf_path'=> $_SERVER['DOCUMENT_ROOT'] . 'civi4/sites/default')
        );
    }

    public function request($action, $entity, $params) {
        $params = array_merge($this->defaults, array('debug' => $this->debug), $params);
        $success = $this->api->{$entity}->{$action}($params);
        return $this->api->result;
    }

    public function getMembershipForDrupalUser($uid) {
        return $this->request('get', 'UFMatch', array(
            'return' => "contact_id",
            'uf_id' => $uid,
            'api.Membership.getsingle' => array('contact_id' => "\$value.contact_id")
        ));
    }

    public function updateMembershipPayment($membership_id, $contact_id, $amount) {
    	return $this->request('create', 'Membership', array(
    		'id' => $membership_id,
    		'contact_id' => $contact_id,
    		'custom_37' => $amount
    	));
    }

    public function updateMembershipInterval($membership_id, $contact_id, $interval) {
    	return $this->request('create', 'Membership', array(
    		'id' => $membership_id,
    		'contact_id' => $contact_id,
    		'custom_36' => $interval
    	));
    }

    public function createActivity($activity_type_id, $target_id, $subject) {
    	return $this->request('create', 'Activity', array(
    		'source_contact_id' => $target_id, // set source to target, because really this is user-initiated
    		'activity_type_id' => $activity_type_id,
    		'target_id' => $target_id,
    		'subject' => $subject
    	));
    }
}
