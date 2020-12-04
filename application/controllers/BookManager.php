<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BookManager extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('book_model');
	}

	public function index()
	{	
		$books = $this->book_model->getAllBook();
		$num_books = count($books);
		$books = array(
			'allbooks' => $books,
			'num_books' => $num_books
		);

		$this->load->view('bookManager_view', $books, FALSE);
	}

	public function getLanguage()
	{
		$language = $this->book_model->getLanguage();
		$language = json_encode($language);
		echo $language;
	}

	public function addBook()
	{	
		$title = $this->input->post('title');
		$authors = $this->input->post('authors');
		$language_code = $this->input->post('language');
		$pages = $this->input->post('num_page');
		$rating = $this->input->post('rating');
		$publication_date = $this->input->post('publication_date');
		$publisher = $this->input->post('publisher');
		if($this->book_model->addBook($title, $authors, $language_code, $pages, $rating, $publication_date, $publisher)){
			echo '<script>alert("Insert book successfully!")</script>';
            redirect(base_url(). 'BookManager','refresh');
		}
	}

	public function editBook($book_id)
	{
		$data = $this->book_model->getBookDetail($book_id);
		$this->load->view('editBook_view', $data[0], FALSE);
	}

	public function saveChange($book_id)
	{
		$title = $this->input->post('title');
		$authors = $this->input->post('authors');
		$language_code = $this->input->post('language');
		$pages = $this->input->post('num_page');
		$rating = $this->input->post('rating');
		$publication_date = $this->input->post('publication_date');
		$publisher = $this->input->post('publisher');
		$this->book_model->saveBook($book_id, $title, $authors, $language_code, $pages, $rating, $publication_date, $publisher);
		echo '<script>alert("Your changes are saved!")</script>';
		redirect(base_url() . 'BookManager','refresh');
	}

	public function deleteBook()
	{	
		$book_id = $this->input->post('book_id');
		$this->book_model->deleteBook($book_id);
		echo "success";
	}

}

/* End of file BookManager.php */
/* Location: ./application/controllers/BookManager.php */