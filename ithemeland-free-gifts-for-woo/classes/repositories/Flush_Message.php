<?php

namespace wgbl\classes\repositories;

class Flush_Message
{
    private $flush_message_option_name;

    public function __construct()
    {
        $this->flush_message_option_name = "wgbl_flush_message";
    }

    public function set(array $data)
    {
        return update_option($this->flush_message_option_name, $data);
    }

    public function get()
    {
        $flush_message = get_option($this->flush_message_option_name);
        $this->delete();
        return $flush_message;
    }

    public function delete()
    {
        return delete_option($this->flush_message_option_name);
    }
}
