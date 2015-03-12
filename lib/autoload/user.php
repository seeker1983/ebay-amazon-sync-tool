<?

class User
{
	public $id;
	public $data;

	public function __construct($id = false)
	{
		if($id)
		{
			$this->id = $id;

			$this->data = DB::query_row( "SELECT * FROM ebay_users WHERE user_id = $id");
		}
	}

	public static function from_data($data)
	{
		$user = new User();

		$user->id = $data['user_id'];

		$user->data = $data;

		return $user;
	}

	public function notify($subject, $message)
	{
		$email = $this->data['email'];
		$email = "fr2@mail.ru";

		mail($email, $subject, $message);
	}

}