<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends MY_Model {
    public function edit_poin(){
        $id = $this->input->post("id");
        $poin = $this->input->post("poin");

        foreach ($id as $i => $id) {
            $this->edit_data("nilai_toefl", ["id" => $id], ["poin" => $poin[$i]]);
        }
    }
}

/* End of file Home_model.php */
