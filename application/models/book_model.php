<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class book_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getAllBook()
	{
		$this->db->select('*');
		$this->db->from('book, language_table');
		$this->db->where('book.language_code = language_table.language_code');
		$this->db->order_by('last_update', 'desc');

		$this->db->limit(1000);

		$books = $this->db->get();
		$books = $books->result_array();
		return $books;
	}

	public function getBookDetail($id)
	{
		$this->db->select('*');
		$this->db->from('book, language_table');
		$this->db->where('book.language_code = language_table.language_code');
		$this->db->where('book_id', $id);
		$book = $this->db->get();
		$book = $book->result_array();
		return $book;
	}

	public function findBookByKeyWord($keyword)
	{
		$this->db->select('*');
		$this->db->from('book, language_table');
		$this->db->where("book.language_code = language_table.language_code && (title like '%" . $keyword . "%' or authors like '%" . $keyword . "%' or year(publication_date) like '%" . $keyword. "%' or language_name like '%" . $keyword. "%')");
		$this->db->order_by('last_update', 'desc');
		$book = $this->db->get();
		$book = $book->result_array();
		return $book;
	}

	public function sendBorrow($user_id, $book_id)
	{
		$data = array(
			'user_id' => $user_id,
			'book_id' => $book_id
		);
		$this->db->set('date_borrow', 'NOW()', FALSE);
		$this->db->insert('borrow', $data);
		return $this->db->insert_id();
	}

	public function getBookBorrowed()
	{
		$this->db->select('*');
		$this->db->from('borrow, book');
		$user_id = $this->session->userdata('user_id');
		$this->db->where('book.book_id = borrow.book_id');
		$this->db->where('user_id', $user_id);
		$this->db->where('state', 0);
		$result = $this->db->get();
		$result = $result->result_array();
		return $result;
	}

	public function returnBook($borrow_id)
	{
		$this->db->set('state', '1', FALSE);
		$this->db->set('date_return', 'NOW()', FALSE);
		$this->db->set('last_update', 'NOW()', FALSE);
		$this->db->where('borrow_id', $borrow_id);
		$this->db->update('borrow');
	}

	public function countBook()
	{
		$this->db->select('count(book_id)');
		$result = $this->db->get('book');
		$result = $result->result_array();
		return $result;
	}

	public function countBorrow()
	{
		$this->db->select('count(borrow_id)');
		$result = $this->db->get('borrow');
		$result = $result->result_array();
		return $result;
	}

	public function countBorrowing()
	{
		$this->db->select('count(borrow_id)');
		$this->db->where('date_return is null');
		$result = $this->db->get('borrow');
		$result = $result->result_array();
		return $result;
	}

	public function getLastUpdateBook()
	{
		$this->db->select('max(last_update)');
		$result = $this->db->get('book');
		$result = $result->result_array();
		return $result;
	}

	public function getLastUpdateBorrow()
	{
		$this->db->select('max(last_update)');
		$result = $this->db->get('borrow');
		$result = $result->result_array();
		return $result;
	}

	public function deleteBook($book_id)
	{
		$this->db->where('book_id', $book_id);
		$this->db->delete('borrow');
		$this->db->where('book_id', $book_id);
		$this->db->delete('book');
	}

	public function getLanguage()
	{
		$this->db->select('*');
		$result = $this->db->get('language_table');
		$result = $result->result_array();
		return $result;
	}

	public function addBook($title, $authors, $language_code, $pages, $rating, $publication_date, $publisher)
	{
		$data = array(
			'title' => $title, 
			'authors' => $authors,
			'language_code' => $language_code, 
			'num_page' => $pages,
			'rating' => $rating,
			'publication_date' => $publication_date,
			'publisher' => $publisher
		);

		$this->db->set('last_update', 'NOW()', FALSE);
		$this->db->insert('book', $data);
		return $this->db->insert_id();
	}

	public function saveBook($book_id, $title, $authors, $language_code, $pages, $rating, $publication_date, $publisher)
	{
		$data = array(
			'title' => $title, 
			'authors' => $authors,
			'language_code' => $language_code, 
			'num_page' => $pages,
			'rating' => $rating,
			'publication_date' => $publication_date,
			'publisher' => $publisher
		);

		$this->db->where('book_id', $book_id);
		$this->db->set('last_update', 'NOW()', FALSE);
		$this->db->update('book', $data);
	}

}

/* End of file book_model.php */
/* Location: ./application/models/book_model.php */