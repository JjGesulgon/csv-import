<?php

namespace App\Repositories;

use App\Contact;

class ContactRepository extends Repository
{
    /**
     * Create new instance of tech stack item repository.
     *
     * @param Contact Tech Stack Item techStackItem model
     */
    public function __construct(Contact $contact)
    {
        parent::__construct($contact);
        $this->contact = $contact;
    }

    /**
      * Get Contacts
      *
      * @return array json object
      */
    public function getContacts()
    {
        return $this->model->all();
    }
}
