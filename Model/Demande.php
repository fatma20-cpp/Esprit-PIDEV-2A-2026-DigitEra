<?php
class Demande {

    private $id_demande;
    private $id_offer;
    private $id_freelancer;
    private $price;
    private $delivery_time;
    private $message;
    private $status;
    private $created_at;

    function __construct($id_offer, $id_freelancer, $price, $delivery_time, $message) {
        $this->id_offer = $id_offer;
        $this->id_freelancer = $id_freelancer;
        $this->price = $price;
        $this->delivery_time = $delivery_time;
        $this->message = $message;
    }

    // GETTERS
    public function getOfferId() { return $this->id_offer; }
    public function getFreelancerId() { return $this->id_freelancer; }
    public function getPrice() { return $this->price; }
    public function getDeliveryTime() { return $this->delivery_time; }
    public function getMessage() { return $this->message; }
    public function getStatus() { return $this->status; }

    // SETTERS
    public function setStatus($status) { $this->status = $status; }
}
?>