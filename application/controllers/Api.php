<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	// public function index()
	// {
	// 	$this->load->view('welcome_message');
	// }

	public function dymmyData()
	{
		$dummyData = [];
		for ($i = 1; $i <= 100; $i++) {
			$dummyData[] = (object) [
				'id' => $i,
				'title' => 'Blog ' . $i,
				'image' => "https://via.placeholder.com/$i", // Use a placeholder image URL
				'category' => 'Technology',
				'timestamp' => date('Y-m-d H:i:s', strtotime("-$i days")),
			];
		}

		// Assuming you're passing the dummy data to your view as $blogs
		$blogs = $dummyData;

		// Return the dummy data as JSON
		header('Content-Type: application/json');
		echo json_encode($blogs);
	}





	public function getIpoData() {
		$result = $this->db->get("ipo_data")->result_array();
		$g = $this->db->get("gmp")->result_array();
		$kkk = $this->db->get("old_gmp")->result_array();
		$ipoList = [
			'ipoList' => $result,
			'g' => $g,
			'fgvshdg' => $kkk,
		];
		echo json_encode(  $ipoList);
	}




	function getUserData($userId)
	{

		echo $_POST;
		global $conn;

		$userId = mysqli_real_escape_string($conn, $userId);

		$sql = "SELECT * FROM users WHERE id = $userId";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// Fetch user data
			$userData = $result->fetch_assoc();
			echo $userData;
		} else {
			// No user found
			echo "user not found ";
			return null;
		}

		if ($userData) {
			// Display user information
			echo "User ID: " . $userData['id'] . "<br>";
			echo "Username: " . $userData['username'] . "<br>";
			echo "Email: " . $userData['email'] . "<br>";
			// Add more fields as needed
		} else {
			echo "User not found";
		}
	}




	function getWordsData() {
		$vocab_words = $this->db->limit(20)->get("vocabulary_words")->result_array();
		$audio_urls = $this->db->limit(20)->get("get_audio_urls")->result_array();
	
		$words_data = [
			'vocab_words' => $vocab_words,
			'audio_urls' => $audio_urls,
		];
	
		header('Content-Type: application/json');  
		echo json_encode($words_data); 
	}
	


  function getAdjectives(){

		$adjective_words = $this->db->limit(50)->get("adjectives_words")->result_array();

		$data = [
			"sttatus" => 1,
			"response" => "response fetched successfully",
			'data' => $adjective_words,
		];

		header('Content-Type: application/json');  
		echo json_encode($data); 
  	}




	  public function getCategoryNameList() {
		// Define the category details
		
		$categories = [
			[
				"categoryName" => "adjectives_words",
				"title" => "Adjectives Words ",
				"sub_title" => "frame with balls for calculating"
			],
			[
				"categoryName" => "adverbs_words",
				"title" => "Adverbs Words",
				"sub_title" => "another sub title"
			],
			[
				"categoryName" => "compound_words",
				"title" => "Compound Words",
				"sub_title" => "yet another sub title"	
			]
		];
	
		$data = [
			"status" => 1,
			"response" => "response fetched successfully",
			"CategoryNameList" => $categories
		];
	
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	

//  TODO : this is the getAdverbs function for the adverbs 

// 	public function flashCardData()   {

// 	if ($_SERVER['REQUEST_METHOD'] === 'GET'){
	
// 		header("HTTP/1.1 405 Method Not Allowed");
// 		header("Allow: POST");
// 		echo json_encode(["error" => "Method Not Allowed"]);
// 		exit;
// 	}

// 	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         $postData = json_decode(file_get_contents('php://input'), true);

//         if (!isset($postData["category_name"])) {
//             header("HTTP/1.1 400 Bad Request");
//             echo json_encode(["error" => "category_name is missing"]);
//             exit;
//         }

//         $categoryName = $postData["category_name"];

 
//         $adverb_words = $this->db->limit(50)->get($categoryName)->result_array();
//         if (!$adverb_words) {
//             header("HTTP/1.1 500 Internal Server Error");
//             echo json_encode(["error" => "Failed to fetch adverb words"]);
//             exit;
//         }

//         foreach ($adverb_words as &$word) {
//             $word['id'] = (int) $word['id'];
//             $word['synonyms'] = json_decode($word['synonyms'], true);
//             $word['antonyms'] = json_decode($word['antonyms'], true);
//         }

//         $response = [
//             "status" => 1,
//             "postdata" => $postData,
//             "response" => "$categoryName response fetched successfully",
//             'words' => $adverb_words,
//         ];

//         header('Content-Type: application/json');
//         echo json_encode($response);
//         exit;
//     }
// }




public function flashCardData() {
    $postData = json_decode(file_get_contents('php://input'), true);

    if (!isset($postData["category_name"])) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["error" => "category_name is missing"]);
        exit;
    }

    $categoryName = $postData["category_name"];

    // $categoryName = $this->input->post('category_name');	
    $adverb_words = $this->db->limit(50)->get($categoryName)->result_array();

    if (!empty($categoryName)) {
        foreach ($adverb_words as &$word) {
            $word['id'] = (int)$word['id'];
            $word['synonyms'] = json_decode($word['synonyms'], true);
            $word['antonyms'] = json_decode($word['antonyms'], true);
        }

        $response = [
            "status" => 1,
            "postdata" => $categoryName,
            "response" => "$categoryName response fetched successfully",
            'words' => $adverb_words,
        ];
    } else {
        $response = [
            "status" => 0,
            "test" => $categoryName,
            "response" => "No data found",
            'words' => '',
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

}
