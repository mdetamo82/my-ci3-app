<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hawala_report_model extends CI_Model
{
    public function get_all_hawalas()
    {
        return $this->db->get('hawalas')->result();
    }

    public function get_hawala_details($hawala_id)
    {
        if (!$hawala_id) return null;
        return $this->db->get_where('hawalas', ['hawala_id' => $hawala_id])->row();
    }

    public function get_transactions($start_date, $end_date, $hawala_id = null)
    {
        $this->db->select('t.*, h.mark');
        $this->db->from('transactions t');
        $this->db->join('hawalas h', 't.hawala_id = h.hawala_id');
        $this->db->where('t.date >=', $start_date);
        $this->db->where('t.date <=', $end_date);

        if (!empty($hawala_id)) {
            $this->db->where('t.hawala_id', $hawala_id);
        }

        $this->db->order_by('t.date', 'ASC');
        return $this->db->get()->result();
    }

    public function get_previous_balance($start_date, $hawala_id)
    {
        if (!$hawala_id) return 0;

        $this->db->select_sum('credit', 'total_credit');
        $this->db->select_sum('debit', 'total_debit');
        $this->db->where('hawala_id', $hawala_id);
        $this->db->where('date <', $start_date);
        $result = $this->db->get('transactions')->row();

        return ($result->total_credit ?? 0) - ($result->total_debit ?? 0);
    }
}
