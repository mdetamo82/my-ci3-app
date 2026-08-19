<?php
class Permission_model extends CI_Model
{
    // Get all available permissions
    public function get_all_permissions()
    {
        return $this->db->get('permissions')->result();
    }

    // Get permissions assigned to a specific group
    public function get_permissions_by_group($group_id)
    {
        $this->db->select('permission_id');
        $this->db->where('group_id', $group_id);
        $query = $this->db->get('group_permissions');
        return array_column($query->result_array(), 'permission_id');
    }

    // Update the group permissions
    public function update_group_permissions($group_id, $permission_ids = [])
    {
        // Remove old permissions
        $this->db->where('group_id', $group_id)->delete('group_permissions');
    
        // Insert new ones
        if (!empty($permission_ids)) {
            $insert_data = [];
            foreach ($permission_ids as $pid) {
                $insert_data[] = [
                    'group_id' => $group_id,
                    'permission_id' => $pid
                ];
            }
            $this->db->insert_batch('group_permissions', $insert_data);
        }
    }

    // Sync permissions from the config file
    public function sync_from_config($config_permissions)
    {
        foreach ($config_permissions as $controller => $methods) {
            foreach ($methods as $method => $desc) {
                $name = $controller . '_' . $method;
                $exists = $this->db->get_where('permissions', ['name' => $name])->row();
                if (!$exists) {
                    $this->db->insert('permissions', [
                        'name' => $name,
                        'description' => $desc,
                        'controller' => $controller,
                        'method' => $method
                    ]);
                }
            }
        }
    }
}
