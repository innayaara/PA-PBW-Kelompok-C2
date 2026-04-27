<?php

class PengaturanTampilanModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getFirstSetting()
    {
        $query = "SELECT * FROM pengaturan_tampilan ORDER BY id ASC LIMIT 1";
        $result = mysqli_query($this->conn, $query);

        if ($result) {
            return mysqli_fetch_assoc($result);
        }

        return [];
    }

    public function updateSetting($id, $data)
    {
        $id = (int) $id;

        $fields = [
            'hero_image', 'hero_eyebrow', 'hero_title_main', 'hero_title_accent', 'hero_title_bottom', 'hero_subtitle',
            'hero_stat_1_num', 'hero_stat_1_label', 'hero_stat_2_num', 'hero_stat_2_label',
            'hero_stat_3_num', 'hero_stat_3_label', 'hero_stat_4_num', 'hero_stat_4_label',
            'about_image', 'about_badge_num', 'about_badge_label', 'about_section_label', 'about_title', 'about_description',
            'feature_1_icon', 'feature_1_title', 'feature_1_desc',
            'feature_2_icon', 'feature_2_title', 'feature_2_desc',
            'feature_3_icon', 'feature_3_title', 'feature_3_desc'
        ];

        $setParts = [];

        foreach ($fields as $field) {
            $value = mysqli_real_escape_string($this->conn, $data[$field] ?? '');
            $setParts[] = "$field = '$value'";
        }

        $setClause = implode(",\n        ", $setParts);

        $query = "UPDATE pengaturan_tampilan SET
        $setClause
        WHERE id = $id";

        return mysqli_query($this->conn, $query);
    }
}
?>