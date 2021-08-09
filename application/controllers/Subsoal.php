<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Subsoal extends MY_Controller {

    
    public function __construct() {
        parent::__construct();
        $this->load->model("Main_model");
        $this->load->model("Other_model");
    }
    
    public function index(){
        // navbar and sidebar
        $data['menu'] = "Subsoal";

        // for title and header 
        $data['title'] = "List Sub Soal";

        // for modal 
        $data['modal'] = [
            "modal_subsoal",
            "modal_setting"
        ];
        
        // javascript 
        $data['js'] = [
            "ajax.js",
            "function.js",
            "helper.js",
            "modules/setting.js",
            "modules/subsoal.js",
            "load_data/subsoal_reload.js",
        ];

        // $this->load->view("pages/subsoal/list-soal", $data);
        $this->load->view("pages/subsoal/list", $data);
    }

    public function edit($id){
        $soal = $this->Main_model->get_one("sub_soal", ["md5(id_sub)" => $id, "hapus" => 0]);
        
        // id soal 
        $data['id'] = $id;
        $data['title'] = "List Soal " . $soal['nama_sub'];
        
        $data['menu'] = "Item";

        // for modal 
        $data['modal'] = [
            "modal_item_soal",
            "modal_setting"
        ];
        
        // javascript 
        $data['js'] = [
            "ajax.js",
            "function.js",
            "helper.js",
            "modules/setting.js",
            "modules/item_soal.js",
            // "load_data/reload_soal_listening.js",
            "load_data/reload_item.js",
        ];

        // $this->load->view("pages/subsoal/list-soal", $data);
        $this->load->view("pages/subsoal/list-item", $data);
    }

    public function hasil($id){
        // navbar and sidebar
        $data['menu'] = "Soal";

        // for title and header 
        $data['title'] = "List Hasil Soal";

        $respon = $this->Main_model->get_all("peserta", ["md5(id_sub)" => $id]);
        $data['respon'] = [];
        foreach ($respon as $i => $respon) {
            $data['respon'][$i] = $respon;
            $jawaban = explode("###", $respon['text']);
            $data['respon'][$i]['text'] = $jawaban;
        }

        $this->load->view("pages/subsoal/hasil-soal", $data);
    }

    public function loadSubSoal(){
        header('Content-Type: application/json');
        $output = $this->subsoal->loadSubSoal();
        echo $output;
    }

    // add 
        public function add(){
            $data = $this->Main_model->add_data("sub_soal", $_POST);
            if($data){
                echo json_encode("1");
            } else {
                echo json_encode("0");
            }
        }

        public function add_item_soal(){
            // var_dump($_POST);
            // var_dump($_FILES);
            // exit();

            $id_sub = $this->input->post("id_sub");
            $soal = $this->Main_model->get_one("sub_soal", ["md5(id_sub)" => $id_sub]);

            $item = $this->Main_model->get_one("item_soal", ["md5(id_sub)" => $id_sub], "urutan", "DESC");
            if($item) {
                $urutan = $item['urutan'] + 1;
            } else {
                $urutan = 1;
            }

            if($_POST['item'] != "gambar"){
                $data = [
                    "id_sub" => $soal['id_sub'],
                    "item" => $this->input->post("item"),
                    "data" => trim($this->input->post("data_soal")),
                    "penulisan" => $this->input->post("penulisan"),
                    "urutan" => $urutan,
                ];
    
                $query = $this->Main_model->add_data("item_soal", $data);
                if($query){
                    echo json_encode(1);
                } else {
                    echo json_encode(0);
                }
            } else {
                $data = [
                    "id_sub" => $soal['id_sub'],
                    "item" => $this->input->post("item"),
                    "data" => "gambar",
                    "penulisan" => $this->input->post("penulisan"),
                    "urutan" => $urutan,
                ];
    
                $query = $this->Main_model->add_data("item_soal", $data);

                $this->upload_image($query, $_FILES);

                if($query){
                    echo json_encode(1);
                } else {
                    echo json_encode(0);
                }
            }

        }

        public function upload_image($id, $file){
            if(isset($file['file']['name'])) {

                $nama_file = $file['file'] ['name']; // Nama Audio
                $size        = $file['file'] ['size'];// Size Audio
                $error       = $file['file'] ['error'];
                $tipe_audio  = $file['file'] ['type']; //tipe audio untuk filter
                $folder      = "./assets/myimg/"; //folder tujuan upload
                $valid       = array('jpg','png','gif','jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); //Format File yang di ijinkan Masuk ke server
                
                if(strlen($nama_file)){   
                     // Perintah untuk mengecek format gambar
                     list($txt, $ext) = explode(".", $nama_file);
                     if(in_array($ext,$valid)){   

                         // Perintah untuk mengupload file dan memberi nama baru
                        switch ($tipe_audio) {
                            case 'image/jpeg':
                                $tipe_audio = "jpg";
                                break;
                            case 'image/png':
                                $tipe_audio = "png";
                                break;
                            case 'image/gif':
                                $tipe_audio = "gif";
                                break;
                            default:
                                break;
                        }

                         $img_peserta = $id.".".$tipe_audio;

                         $tmp = $file['file']['tmp_name'];
                        
                         
                        if(move_uploaded_file($tmp, $folder.$img_peserta)){
                            $this->Main_model->edit_data("item_soal", ["id_item" => $id], ["data" => $img_peserta]);
                            return 1;
                            
                        } else { // Jika Audio Gagal Di upload 
                            return 0;
                        }
                     } else{ 
                        return 2;
                    }
            
                }
                
            }
        }
    // add 
    
    // get 
        public function get(){
            $id_sub = $this->input->post("id_sub");

            $data = $this->Main_model->get_one("sub_soal", ["id_sub" => $id_sub]);
            echo json_encode($data);
        }

        public function get_all_item(){
            $id_sub = $this->input->post("id_sub");

            $data = $this->Main_model->get_one("sub_soal", ["md5(id_sub)" => $id_sub]);

            $structure = $this->Main_model->get_all("item_soal", ["md5(id_sub)" => $id_sub], "urutan", "ASC");
            $data['item'] = [];

            $j = 1;
            foreach ($structure as $i => $soal) {
                if($soal['item'] == "soal"){

                    // from json to array 
                    // var_dump($soal);
                    $string = trim(preg_replace('/\s+/', ' ', $soal['data']));
                    // $txt_soal = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $soal['data']), true );
                    $txt_soal = json_decode($string, true );
                    
                    
                    // var_dump($txt_soal);

                    if($soal['penulisan'] == "RTL"){
                        $no = $this->Other_model->angka_arab($j).". ";
                        $tes['soal'] = str_replace("{no}", $no, $txt_soal['soal']);
                    } else {
                        $no = $j.". ";
                        $tes['soal'] = str_replace("{no}", $no, $txt_soal['soal']);
                    }

                    $data['item'][$i]['id_item'] = $soal['id_item'];
                    $data['item'][$i]['item'] = $soal['item'];
                    $data['item'][$i]['data']['soal'] = $tes['soal'];
                    $data['item'][$i]['data']['pilihan'] = $txt_soal['pilihan'];
                    $data['item'][$i]['data']['jawaban'] = $txt_soal['jawaban'];
                    $data['item'][$i]['penulisan'] = $soal['penulisan'];
                    
                    $j++;

                } else if($soal['item'] == "soal esai"){

                    // from json to array 
                    // var_dump($soal);
                    $string = trim(preg_replace('/\s+/', ' ', $soal['data']));
                    // $txt_soal = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $soal['data']), true );
                    $txt_soal = json_decode($string, true );
                    
                    
                    // var_dump($txt_soal);

                    if($soal['penulisan'] == "RTL"){
                        $no = $this->Other_model->angka_arab($j).". ";
                        $tes['soal'] = str_replace("{no}", $no, $txt_soal['soal']);
                    } else {
                        $no = $j.". ";
                        $tes['soal'] = str_replace("{no}", $no, $txt_soal['soal']);
                    }

                    $data['item'][$i]['id_item'] = $soal['id_item'];
                    $data['item'][$i]['item'] = $soal['item'];
                    $data['item'][$i]['data']['soal'] = $tes['soal'];
                    $data['item'][$i]['data']['jawaban'] = $txt_soal['jawaban'];
                    $data['item'][$i]['penulisan'] = $soal['penulisan'];
                    
                    $j++;

                } else if($soal['item'] == "petunjuk" || $soal['item'] == "audio"){
                    $data['item'][$i] = $soal;
                    if($soal['item'] == "audio"){
                        $audio = $this->Main_model->get_one("audio", ["id_audio" => $soal['data']]);
                        $data['item'][$i]['file'] = $audio['nama_file'];
                        $data['item'][$i]['nama'] = $audio['nama_audio'];
                    }
                } else if($soal['item'] == "gambar"){
                    $data['item'][$i] = $soal;
                }
            }

            echo json_encode($data);
        }

        public function get_item(){
            $id_item = $this->input->post("id_item");
            $item = $this->Main_model->get_one("item_soal", ["id_item" => $id_item]);
            
            if($item['item'] == "soal"){
                $data = $item;

                // $item = explode("###", $item['data']);
                // from json to array 
                $string = trim(preg_replace('/\s+/', ' ', $item['data']));
                // $txt_soal = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $soal['data']), true );
                $item = json_decode($string, true );
                // $item = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $item['data']), true );

                $data['soal'] = $item['soal'];
                // $data['soal'] = $item[0];
                // $pilihan = explode("///", $item[1]);

                // $data['pilihan_a'] = $pilihan[0];
                // $data['pilihan_b'] = $pilihan[1];
                // $data['pilihan_c'] = $pilihan[2];
                $data['pilihan'] = $item['pilihan'];
                $data['jawaban'] = $item['jawaban'];
            } else if($item['item'] == "soal esai"){
                $data = $item;

                // $item = explode("###", $item['data']);
                // from json to array 
                $string = trim(preg_replace('/\s+/', ' ', $item['data']));
                // $txt_soal = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $soal['data']), true );
                $item = json_decode($string, true );
                // $item = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $item['data']), true );

                $data['soal'] = $item['soal'];
                $data['jawaban'] = $item['jawaban'];
            } else if($item['item'] == "petunjuk" || $item['item'] == "audio"){
                $data = $item;
            }

            echo json_encode($data);
        }
    // get 

    // edit 
        public function update(){
            $id_sub = $this->input->post("id_sub");
            
            unset($_POST['id_sub']);

            $data = $this->Main_model->edit_data("sub_soal", ["id_sub" => $id_sub], $_POST);
            if($data){
                echo json_encode("1");
            } else {
                echo json_encode("0");
            }
        }

        public function edit_item(){
            $id_item = $this->input->post("id_item");

            $data = [
                "data" => $this->input->post("data_soal"),
                "penulisan" => $this->input->post("penulisan"),
            ];

            $query = $this->Main_model->edit_data("item_soal", ["id_item" => $id_item], $data);
            if($query){
                echo json_encode(1);
            } else {
                echo json_encode(0);
            }
        }

        public function edit_urutan(){
            $id_item = $this->input->post("id_item");

            $i = 1;
            foreach ($id_item as $item) {
                $this->Main_model->edit_data("item_soal", ["id_item" => $item], ["urutan" => $i]);
                $i++;
            }

            echo json_encode(1);
        }
    // edit 

    // delete 
        public function delete(){
            $id_sub = $this->input->post("id_sub");

            $data = $this->Main_model->edit_data("sub_soal", ["id_sub" => $id_sub], ["hapus" => 1]);
            if($data){
                echo json_encode("1");
            } else {
                echo json_encode("0");
            }
        }

        public function hapus_item(){
            $id_item = $this->input->post("id_item");

            $item = $this->Main_model->get_one("item_soal", ["id_item" => $id_item]);

            if($item['item'] == "gambar"){
                unlink('./assets/myimg/'.$item['data']);
            }

            $id_sub = $item['id_sub'];
            $urutan = $item['urutan'];

            $all_item = $this->Main_model->get_all("item_soal", ["id_sub" => $id_sub, "urutan > ", $urutan]);
            foreach ($all_item as $item) {
                $urutan = $item['urutan'] - 1;
                $this->Main_model->edit_data("item_soal", ["id_item" => $item['id_item']], ["urutan" => $urutan]);
            }

            $data = $this->Main_model->delete_data("item_soal", ["id_item" => $id_item]);
            if($data){
                echo json_encode("1");
            } else {
                echo json_encode("0");
            }
        }
    // delete

    // other 
        function hari_ini($hari){
            // $hari = date ("D");
        
            switch($hari){
                case 'Sun':
                    $hari_ini = "Minggu";
                break;
        
                case 'Mon':			
                    $hari_ini = "Senin";
                break;
        
                case 'Tue':
                    $hari_ini = "Selasa";
                break;
        
                case 'Wed':
                    $hari_ini = "Rabu";
                break;
        
                case 'Thu':
                    $hari_ini = "Kamis";
                break;
        
                case 'Fri':
                    $hari_ini = "Jumat";
                break;
        
                case 'Sat':
                    $hari_ini = "Sabtu";
                break;
                
                default:
                    $hari_ini = "Tidak di ketahui";		
                break;
            }
        
            return $hari_ini;
        
        }

        public function tgl_indo($tgl){
            $data = explode("-", $tgl);
            $hari = $data[0];
            $bulan = $data[1];
            $tahun = $data[2];
    
            if($bulan == "01") $bulan = "Januari";
            if($bulan == "02") $bulan = "Februari";
            if($bulan == "03") $bulan = "Maret";
            if($bulan == "04") $bulan = "April";
            if($bulan == "05") $bulan = "Mei";
            if($bulan == "06") $bulan = "Juni";
            if($bulan == "07") $bulan = "Juli";
            if($bulan == "08") $bulan = "Agustus";
            if($bulan == "09") $bulan = "September";
            if($bulan == "10") $bulan = "Oktober";
            if($bulan == "11") $bulan = "November";
            if($bulan == "12") $bulan = "Desember";
    
            return $hari . " " . $bulan . " " . $tahun;
        }
    // other

    public function input($id = 21){
        $this->Main_model->delete_data("item_soal", ["id_sub" => $id]);
        $data = [
            [
                "tipe" => "petunjuk",
                "data" => "
                    Questions 1 through 10
                    The food we eat seems to have profound effects on our health. Although science has made enormous steps in making food more <b>fit</b> to eat, it has, at the same time, made many foods unfit to eat. Some research has shown that perhaps eighty percent of all human illness are related to diet and forty percent of cancer is related to the diet as well, especially cancer of the colon. People of different cultures are more <b>prone</b> to contract certain illness because of the characteristic foods they consume.
                    That food is related to illness is not a new discovery. In 1945, government researchers realized that nitrates and nitrites (commonly used to persevere color in meats) as well as other food additives caused cancer. Yet, <b>these carcinogenic additives</b> remain in our food, and it becomes more difficult all the time to know which ingredients on the packaging labels of processed food are helpful or harmful. 
                    The additives that we eat are not all so direct. Farmers often give penicillin to cattle and poultry, and because of this, penicillin has been found in the milk of treated cows. Sometimes similar drugs are administered to animal not for medicinal purposes, but for financial reasons. The farmers are simply trying to fatten the animals in order to obtain a higher price on the market. Although the Food and Drug Administration (FDA) has tried repeatedly to control these procedures, the practices continue.
                    A healthy diet is directly related to good health. Often we are unaware of the detrimental substances we ingest. Sometimes well-meaning farmers or others who do not realize the consequences add these substances to food without our knowledge.
                "
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 1,
                    "soal" => "How has science done a disservice to people?",
                    "pilihan" => [
                        "Because of science, disease caused by contaminated food has been virtually eradicated.",
                        "It has caused a lack of information concerning the value of food.",
                        "As a result of scientific intervention, some potentially harmful substances have been added to our food.",
                        "The scientists have preserved the color of meats, but not of vegetables.",
                    ],
                    "jawaban" => "As a result of scientific intervention, some potentially harmful substances have been added to our food.",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 2,
                    "soal" => "The word “prone” in the last line (paragraph 1) is nearest in meaning to………",
                    "pilihan" => [
                        "Supine",
                        "Unlikely",
                        "Healthy",
                        "Predisposed",
                    ],
                    "jawaban" => "Predisposed",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 3,
                    "soal" => "What are nitrates used for?",
                    "pilihan" => [
                        "The preserve flavor in packaging foods.",
                        "They preserve the color of meats.",
                        "They are the objects of research.",
                        "They cause the animals to become fatter.",
                    ],
                    "jawaban" => "They preserve the color of meats.",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 4,
                    "soal" => "FDA means…………..",
                    "pilihan" => [
                        "Food Direct Additives",
                        "Final Difficult Analysis",
                        "Food and Drug Administration",
                        "Federal Dairy Additives",
                    ],
                    "jawaban" => "Food and Drug Administration",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 5,
                    "soal" => "The word “these” in paragraph 2 refers to …………..",
                    "pilihan" => [
                        "Meats",
                        "Colors",
                        "Researchers",
                        "Nitrates and nitrites",
                    ],
                    "jawaban" => "Nitrates and nitrites",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 6,
                    "soal" => "In paragraph 2, the word “carcinogenic” is closest meaning to …………….",
                    "pilihan" => [
                        "Trouble-making",
                        "Color-retaining",
                        "Money-making",
                        "Cancer-making",
                    ],
                    "jawaban" => "Cancer-making",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 7,
                    "soal" => "All of the following statements are true EXCEPT ……….",
                    "pilihan" => [
                        "Drugs are always given to animals for medical reasons.",
                        "Some of the additives in our food are added to the food itself and some are given to the living animals.",
                        "Researchers have known about the potential hazards of food additives for more than forty-five years.",
                        "Food may cause forty percent of the cancer in the world.",
                    ],
                    "jawaban" => "Drugs are always given to animals for medical reasons.",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 8,
                    "soal" => "The word “additives” in paragraph 2 is closest meaning to ………….",
                    "pilihan" => [
                        "Added substances",
                        "Dangerous substances",
                        "Natural substances",
                        "Benign substances",
                    ],
                    "jawaban" => "Added substances",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 9,
                    "soal" => "What is the best title for this passage?",
                    "pilihan" => [
                        "Harmful and harmless substances in food",
                        "Improving health through a natural diet",
                        "The food you eat can affect your health",
                        "Avoiding injurious substances in food",
                    ],
                    "jawaban" => "The food you eat can affect your health",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 10,
                    "soal" => "In paragraph 1, the word “fit” is closest in meaning to …………..",
                    "pilihan" => [
                        "Athletic ",
                        "Suitable",
                        "Tasty",
                        "Adaptable",
                    ],
                    "jawaban" => "Suitable",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 11,
                    "soal" => "The fact that the topic has been known for some time is discussed in paragraphs?",
                    "pilihan" => [
                        "Paragraph 1",
                        "Paragraph 2",
                        "Paragraph 3",
                        "Paragraph 4",
                    ],
                    "jawaban" => "Paragraph 2",
                ]
            ],
            [
                "tipe" => "petunjuk",
                "data" => "
                    <p>The ancient Egyptians firmly believed in the afterlife and spent their time on earth preparing for it. Elaborate burial ritual included preparing the burial site, providing for all of the deceased’s material needs (food, clothing, jewels, and tools of their trade), and preserving the corpse so that it would not <b>decay</b>. This preservation was <b>accomplished</b> through a process of mummification. The ancients left no written accounts as to examine mummies and establish their own theories. The embalming process might have taken up to seventy days for the pharaohs and nobility and only a few days for the poor. The embalmers spread a variety of compounds of salt, spices, and resins in and over the corpse to preserve it. <b>They</b> followed this with a prescribed wrapping, a procedure in which they wound strips of fine linen around, over, and under the body while placing various <b>amulets</b> within the wrappings to protect the deceased from harm on the long journey to the afterlife. They also painted resins over the wrapped linen. Finally, a pharaoh or noble would have been encased in a wooden box before being placed in a sarcophagus.</p>
                "
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 12,
                    "soal" => "How have we been able to learn about the mummification process?",
                    "pilihan" => [
                        "Accurate records have been handed down to us.",
                        "Interviews with embalmers who still use the process have revealed the secret.",
                        "After studying mummies, scientists have developed their own theories.",
                        "Chemical analysis of the compounds has led us to an explanation of the method used.",
                    ],
                    "jawaban" => "After studying mummies, scientists have developed their own theories.",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 13,
                    "soal" => "The word “they” in sentence 7 refers to ……………..",
                    "pilihan" => [
                        "Embalmers",
                        "Spices",
                        "Pharaohs",
                        "The poor",
                    ],
                    "jawaban" => "Embalmers",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 14,
                    "soal" => "The embalming process can best be described as ………….",
                    "pilihan" => [
                        "Lengthy and complicated",
                        "Short and simple",
                        "Strict and unfaltering",
                        "Wild and terrifying",
                    ],
                    "jawaban" => "Lengthy and complicated",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 15,
                    "soal" => "The word “decay” in sentence 2 is closest meaning to …………..",
                    "pilihan" => [
                        "Die",
                        "Deteriorate",
                        "Embalm",
                        "Rejuvenate",
                    ],
                    "jawaban" => "Deteriorate",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 16,
                    "soal" => "All of the following statements are true EXCEPT …………..",
                    "pilihan" => [
                        "Bodies were preserved as a matter of religious belief.",
                        "All mummies took seventy days to complete.",
                        "Special compounds were used to embalm the bodies.",
                        "It has been difficult to determine the process used.",
                    ],
                    "jawaban" => "All mummies took seventy days to complete.",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 17,
                    "soal" => "Why did the ancient Egyptians mummify the deceased?",
                    "pilihan" => [
                        "To preserve the body from destruction",
                        "To scare tomb robbers",
                        "To encase the body in a sarcophagus",
                        "To protect the body from harm on the journey to the afterlife",
                    ],
                    "jawaban" => "To preserve the body from destruction",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 18,
                    "soal" => "It can be inferred that the Egyptians buried food, clothing, jewels, and tools with the deceased because …………..",
                    "pilihan" => [
                        "The family did not want anyone else to share them.",
                        "That was the wish of the deceased.",
                        "They were afraid.",
                        "The deceased would need them while en-route to the afterlife.",
                    ],
                    "jawaban" => "The deceased would need them while en-route to the afterlife.",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 19,
                    "soal" => "The word “amulets” in sentence 7 is closest meaning to ……………",
                    "pilihan" => [
                        "Weapons",
                        "Coins",
                        "Charms",
                        "Curses",
                    ],
                    "jawaban" => "Charms",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 20,
                    "soal" => "In sentence 3 “accomplished” is closest in meaning to ……………",
                    "pilihan" => [
                        "Performed",
                        "Forsaken",
                        "Reproduced",
                        "Dwindled",
                    ],
                    "jawaban" => "Performed",
                ]
            ],
            [
                "tipe" => "petunjuk",
                "data" => "
                    <p>Theodore Seuss Geisel became a writer of children’s books quite by accident. After college in the United States, he did his graduate work in England and France, with the intention of becoming a professor of English. After graduation, instead of pursuing his intended goal, he was hired by the Standard Oil Company as <b>its</b> advertising artist. It was on a trip across the Atlantic from Europe in 1936 that his writing career was born; on that trip, he wrote a <b>nonsense</b> poem to the <b>beat</b> of the ship’s engine. This poem, “And to Think That I Saw It on Mulberry Street,” was published as a book in 1937.</p>
                    <p>It took 20 years for Theodore Geisel to achieve fame as an author. It was the publication of {The Cat in The Hat} in 1957 that made him a household name. Using the <b>pseudonym</b> Dr. Seuss, Geisel founded the publishing company Beginner Books and began <b>churning out</b> hit after hit.</p>
                    <p>A number of factors contributed to the success of the Dr. Seuss books. Some of the most frequently mentioned reasons are that his children’s books have a catchy rhythm pattern and rhyme scheme that differentiate them from all others and make them easy to read and imposible to forget. Furthermore, his tales are of a preposterous nature, with unusual characters and objects that provide a sense of wonder to children and adults alike. Finally, these tales are accompanied by whimsical drawings that are as enjoyable to look at as the stories are fun to read.</p>
                    <p>Theodore Seuss Geisel authored more than 50 books in the years from 1937 to 1990, and one of the books, {How the Grinch Stole Christmas}, was even turned into a holiday cartoon for television. His last book, called {Oh, the Places You’ll Go}, was published in 1990, one year before his death. This beloved writer will remain a favorite author of children for generations to come.</p>
                "
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 21,
                    "soal" => "It is stated in the passage that Theodore Geisel ......",
                    "pilihan" => [
                        "Planned to be an English professor",
                        "Intended to live permanently in Europe",
                        "Wrote hundreds of books",
                        "Had a friend who owned a publishing company",
                    ],
                    "jawaban" => "Planned to be an English professor",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 22,
                    "soal" => "The possessive “its” in paragraph 1 refers to .......",
                    "pilihan" => [
                        "A professor of English",
                        "His intended goal",
                        "The Standard Oil Company",
                        "A trip across the Atlantic",
                    ],
                    "jawaban" => "The Standard Oil Company",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 23,
                    "soal" => "According to the passage, Theodore Seuss Geisel wrote the poem “And to Think That I Saw It on Mulberry Street.” .........",
                    "pilihan" => [
                        "In college",
                        "While living in England",
                        "On an ocean voyage",
                        "On Mulberry Street",
                    ],
                    "jawaban" => "On an ocean voyage",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 24,
                    "soal" => "The word “nonsense” in paragraph 1 is closest in meaning to which of the following?",
                    "pilihan" => [
                        "Insensitive",
                        "Paradoxical",
                        "Inspirational",
                        "Senseless",
                    ],
                    "jawaban" => "Senseless",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 25,
                    "soal" => "The word “beat” in paragraph 1 could best be replaced by ......",
                    "pilihan" => [
                        "Rhythm",
                        "Pressure",
                        "Win",
                        "Power",
                    ],
                    "jawaban" => "Rhythm",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 26,
                    "soal" => "A “pseudonym” in paragraph 2 is most likely ......",
                    "pilihan" => [
                        "A publishing company",
                        "A fictitious name",
                        "A book title",
                        "A kind of profession",
                    ],
                    "jawaban" => "A fictitious name",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 27,
                    "soal" => "The expression “churning out” in paragraph 2 could best be replaced by ......",
                    "pilihan" => [
                        "Reading",
                        "Naming",
                        "Producing",
                        "Solving",
                    ],
                    "jawaban" => "Producing",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 28,
                    "soal" => "Where in the passage does the author mention the illustrations that Dr. Seuss included with his stories?",
                    "pilihan" => [
                        "The fourth sentence in paragraph 1",
                        "The second sentence in paragraph 2",
                        "The fourth sentence in paragraph 3",
                        "The second sentence in paragraph 4",
                    ],
                    "jawaban" => "The fourth sentence in paragraph 3",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 29,
                    "soal" => "It can be determined from the passage that Theodore Geisel died .......",
                    "pilihan" => [
                        "In 1957",
                        "In 1989",
                        "In 1990",
                        "In 1991",
                    ],
                    "jawaban" => "In 1991",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 30,
                    "soal" => "Which paragraph explains the reasons for the success of the Dr. Seuss books ......",
                    "pilihan" => [
                        "The first paragraph",
                        "The second paragraph",
                        "The third paragraph",
                        "The last paragraph",
                    ],
                    "jawaban" => "The third paragraph",
                ]
            ],
            [
                "tipe" => "petunjuk",
                "data" => "
                    <p>During a relatively short period at the end of the nineteenth century, George Eastman was instrumental in transforming the labor-intensive and expensive art of photography into a popular and <b>affordable</b> hobby. The phenomenal success that Eastman had in these early years of his business was in most part due to the successful innovation that opened up photography to the population as a whole.</p>
                    <p>While Eastman was working as a bookkeeper in a bank on Rochester, New York, he spent his leisure time working on a process for making dry plates. By 1880, Eastman had perfected the process. Without leaving his job at the bank, he established the Eastman Dry Plate Company. The business grew so quickly that by 1881 Eastman had <b>given up</b> his job at the bank in order to develop the business.</p>
                    <p>Changes to simplify the process of taking photographs followed one after another. In 1884, Eastman took a step that made photography a less cumbersome process: he replaced the unwieldy glass plates with paper backed roll film. Four years later, the hand-held kodak was introduced. This camera came loaded with enough film to take a hundred photographs and produced round-shaped pictures approximately six centimeters in diameter. Something that made this camera very popular was that a photographer did not need to know how to develope film in order to use it; after using up the film, the photographer sent the loaded camera back to the factory, where the photographs were processed and the camera was reloaded and returned to its owner. In 1892, another innovation, film that could be loaded in the daylight, allowed amateur photographers to load their own film successfully.</p>
                "
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 31,
                    "soal" => "The word “affordable” in paragraph 1 is closest in meaning to .........",
                    "pilihan" => [
                        "Costly",
                        "Priceless",
                        "Cheap",
                        "Valued",
                    ],
                    "jawaban" => "Cheap",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 32,
                    "soal" => "How did photography apparently change at the end of the nineteenth century?",
                    "pilihan" => [
                        "It required more labor.",
                        "It began costing more.",
                        "It became more widespread.",
                        "It became more of an art.",
                    ],
                    "jawaban" => "It became more widespread.",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 33,
                    "soal" => "According to the passage, what contributed to Eastman’s incredible success?",
                    "pilihan" => [
                        "His ability as a photographer",
                        " His ability to come up with new ideas",
                        "A phenomenal amount of luck",
                        "His ability to relate to the population as a whole",
                    ],
                    "jawaban" => " His ability to come up with new ideas",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 34,
                    "soal" => "It can be inferred from the passage that Eastman kept his job at the bank for how long after he established his own company?",
                    "pilihan" => [
                        "One year",
                        "Two years",
                        "Twelve years",
                        "Four years",
                    ],
                    "jawaban" => "One year",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 35,
                    "soal" => "The expression “given up” in paragraph 2 is closest in meaning to which of the following?",
                    "pilihan" => [
                        "Quit",
                        "Handed it",
                        "Misunderstood",
                        "Gotten",
                    ],
                    "jawaban" => "Quit",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 36,
                    "soal" => "It can be inferred from the passage that, prior to 1884, photography .......",
                    "pilihan" => [
                        "Used paper-backed film",
                        "Required glass plates",
                        "Was possible with a hand-held camera",
                        "Was a simple process",
                    ],
                    "jawaban" => "Required glass plates",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 37,
                    "soal" => "Where in the passage does the author indicate what product came on the market in 1888 ?",
                    "pilihan" => [
                        "The second sentence in paragraph 1",
                        "The first sentence in paragraph 2",
                        "The third sentence in paragraph 3",
                        "The sixth sentence in paragraph 3",
                    ],
                    "jawaban" => "The third sentence in paragraph 3",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 38,
                    "soal" => "To get the film for the hand-held kodak developed, the photographer .......",
                    "pilihan" => [
                        "Developed the film at home",
                        "Took the film to a local film developer",
                        "Mailed the film to the factory",
                        "Sent the camera, with film, to the factory",
                    ],
                    "jawaban" => "Sent the camera, with film, to the factory",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 39,
                    "soal" => "Who apparently loaded the film into a hand-held kodak ?",
                    "pilihan" => [
                        "The camera’s owner",
                        "An amateur photographer",
                        "A factory worker",
                        "A professional photographer",
                    ],
                    "jawaban" => "A factory worker",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 40,
                    "soal" => "The paragraph following the passage most likely discusses ........",
                    "pilihan" => [
                        "Another company started by Eastman",
                        "The effect of daylight-loading film on amateur photography",
                        "The predecessor to the hand-held kodak developed by Eastman",
                        "Further innovations in professional photography",
                    ],
                    "jawaban" => "The effect of daylight-loading film on amateur photography",
                ]
            ],
            [
                "tipe" => "petunjuk",
                "data" => "
                    <p>In 1796, George Washington, the first president of the United States, resigned after completing two four-year term in office. He had remained in the service of his country until he was assured that it could continue and succeed without his leadership. John Adam took over Washington’s position as president in a smooth and <b>bloodless</b> change of power that was unusual for its time.</p>
                    <p>By the end of Washington’s presidency, the American government had been established. The three branches of government had been set up and were in <b>working</b> order. The debt had been assumed, and funds had been collected; treaties with major European powers had been signed, and challenges to the new government authorities had been firmly met. However, when Washington left office, there were still some unresolved problems. Internationally, France was in turmoil and on the brink of war; <b>domestically</b>, the contest for political control was a major concern. In addition, there was still some resistance to governmental policies.</p>
                    <p>It was within this context that Washington made his farewell address to the nation. In the address published in a Philadelphia newspaper, Washington advised his fellow politicians to base their views and decisions on the bedrock of enduring principles. He further recommended a firm adherence to the Constitution because he felt that this was necessary for the <b>survival</b> of the young country. He asked that credit be used sparingly and expressed concerns about the unity, the independence, and the future of the young country. In regard to relations with foreign powers, he encouraged the country not to be divided by the conflicts in Europe. Stating that foreign influences were the foe of the republican government, he maintained that relations were to be strictly commercial and not political. He pleaded with the American public to guard their freedoms jealously. Finally, he reminded all citizens of the need for religion and morality and stated his belief that one cannot have one without the other.</p>
                "
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 41,
                    "soal" => "The paragraph preceding the passage most probably discussed ......",
                    "pilihan" => [
                        "The Revolutionary war",
                        "George Washington’s presidency",
                        "European wars",
                        "The writing of the Constitution",
                    ],
                    "jawaban" => "George Washington’s presidency",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 42,
                    "soal" => "Which of the following would be the best title for this passage?",
                    "pilihan" => [
                        "The First President of the United States",
                        "John Adams Takes Over the Presidency",
                        "Challenges Facing the New U.S Government",
                        "George Washington’s Farewell to office",
                    ],
                    "jawaban" => "George Washington’s Farewell to office",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 43,
                    "soal" => "The pronoun “it” in paragraph 1 refers to .......",
                    "pilihan" => [
                        "Office",
                        "Service",
                        "Country",
                        "Leadership",
                    ],
                    "jawaban" => "Country",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 44,
                    "soal" => "It can be inferred from the passage that John Adams .......",
                    "pilihan" => [
                        "Was the second president of the United States",
                        "Had a new idea",
                        "Also served as president for eight years",
                        "Was a part of a bloody change of power",
                    ],
                    "jawaban" => "Was the second president of the United States",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 45,
                    "soal" => "The word “bloodless” in paragraph 1 could best be replaced by ........",
                    "pilihan" => [
                        "Unhealthy",
                        "Deathly",
                        "Inorganic",
                        "Nonviolent",
                    ],
                    "jawaban" => "Nonviolent",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 46,
                    "soal" => "The word “working” in paragraph 2 is closest in meaning to which of the following?",
                    "pilihan" => [
                        "Laboring",
                        "Functioning",
                        "Toiling",
                        "Producing",
                    ],
                    "jawaban" => "Functioning",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 47,
                    "soal" => "According to the passage, what had occurred by the end of George Washington’s presidency?",
                    "pilihan" => [
                        "The debt had been paid off",
                        "A three-branch government was being considered",
                        "Treaties were in place with European governments",
                        "The government had begun distributing funds",
                    ],
                    "jawaban" => "Treaties were in place with European governments",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 48,
                    "soal" => "The word “domestically” in paragraph 2 is closest in meaning to the expression ......",
                    "pilihan" => [
                        "Within the individual",
                        "Within the home",
                        "Within the country",
                        "Within the world",
                    ],
                    "jawaban" => "Within the country",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 49,
                    "soal" => "The word “survival” in paragraph 3 is closest in meaning to .......",
                    "pilihan" => [
                        "Continued life",
                        "Renewed birth",
                        "Successful transition",
                        "Systematic growth",
                    ],
                    "jawaban" => "Continued life",
                ]
            ],
            [
                "tipe" => "soal",
                "data" => [
                    "no" => 50,
                    "soal" => "Where in the passage does the author mention Washington’s belief about a U.S document?",
                    "pilihan" => [
                        "The second sentence in paragraph 1",
                        "The fourth sentence in paragraph 2",
                        "The third sentence in paragraph 3",
                        "The sixth sentence in paragraph 3",
                    ],
                    "jawaban" => "The third sentence in paragraph 3",
                ]
            ]
        ];

        foreach ($data as $i => $data) {
            $datas['id_sub'] = $id;
            $datas['item'] = $data['tipe'];

            if($data['tipe'] == 'soal'){
                $pilihan = "";
                foreach ($data['data']['pilihan'] as $pil) {
                    $pilihan .= "\"".$pil."\",";
                }
                $pilihan = substr($pilihan, 0, -1);
                $data_soal = "{\"soal\":\"<p>{no}".str_replace('"', '\"', $data['data']['soal'])."</p>\",\"pilihan\":[".$pilihan."],\"jawaban\":\"".$data['data']['jawaban']."\"}";
            } elseif($data['tipe'] == "audio") {
                $audio = $this->Main_model->get_one("audio", ["nama_audio" => str_replace(".mp3", "", $data['data'])]);
                $data_soal = $audio['id_audio'];
            } elseif ($data['tipe'] == "petunjuk") {
                $data_soal = $data['data'];
            }

            $datas['data'] = $data_soal;
            $datas['penulisan'] = "LTR";
            $datas['urutan'] = $i + 1;

            $this->subsoal->add_data("item_soal", $datas);
        }

        // echo "Selesai";
        redirect(base_url('subsoal'));
    }
}

/* End of file Soal.php */
