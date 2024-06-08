<?php
// application/models/AdminModel.php

class AdminModel extends CI_Model
{

    public function saveUser($username, $email, $password)
    {



        $existingEmail = $this->db->get_where('users', array('email' => $email))->row();

        if ($existingEmail) {
            return false;
        }

        $data = array(
            'username' => $username,
            'email' => $email,
            'password' => $password
        );

        // $this->db->insert('users', $data);



        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            log_message('error', 'User insertion failed: ' . $this->db->error());
            return false;
        }
    }
}
