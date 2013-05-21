<?php

class app
{
	public function __construct()
	{
		require '../config.php';
		require '../includes/mysql.php';
		require '../includes/constants.php';

		$this->db = new mysql($hostname, $username, $password, $database);


		$user_id = (isset($_GET['u'])) ? $_GET['u'] : 0;

		if ($user_id && $row = $this->getUser($user_id))
		{
			$row2 = $this->getLastFeed($user_id);

			if (!$row2)
			{
				$row2['post_text'] = 'Kein Beitrag';
			}

			$this->outImage(
				$row['user_avatar'],
				$row['username'],
				$this->formatTime($row2['post_time']),
				$this->formatText($row2['post_text'])
			);
		}
		else
		{
			$this->outImage();
		}
	}

	private function formatTime($time)
	{
		return date('H:i d.m.Y', $time) . ' Uhr';
	}

	private function formatText($text, $length = 47)
	{
		if (strlen($text) > $length)
		{
			$text = substr($text, 0, $length) . '...';
		}

		return $text;
	}

	private function getLastFeed($user_id)
	{
		$res = $this->db->query('

			SELECT post_text, post_time
			FROM ' . POSTS_TABLE . '
			WHERE user_id = ' . $user_id . '
			ORDER BY post_id DESC
			LIMIT 1
		');

		$row = $this->db->fetch_array($res);
		$this->db->free_result($res);

		return $row;
	}

	private function getUser($user_id)
	{
		$res = $this->db->query('

			SELECT username, user_avatar
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . $user_id
		);
		$row = $this->db->fetch_array($res);
		$this->db->free_result($res);

		return $row;
	}

	private function outImage($avatar = false, $username = '', $time = '', $text = '')
	{
		header('Content-type: image/jpeg');

		$bg = imagecreatefromJPEG('app.jpeg');

		$blue = imagecolorallocate($bg, 38, 103, 127);
		$grey = imagecolorallocate($bg, 110, 110, 110);
		$greywhite = imagecolorallocate($bg, 201, 201, 201);

		imagettftext($bg, 8, 0, 87, 22, $blue, 'verdanab.ttf', $username);
		imagettftext($bg, 8, 0, 100 + strlen($username) * 7, 22, $greywhite, 'verdana.ttf', $time);
		imagettftext($bg, 8, 0, 87, 44, $grey, 'verdana.ttf', $text);

		if ($avatar)
		{
			if (file_exists($avatar))
			{
				$avatar = '../images/avatar/mini/' . $avatar;
				$size = getimagesize($avatar);

				switch ($size[2])
				{
					case 1:
						$image = imagecreatefromgif($avatar);
					break;
					case 2:
						$image = imagecreatefromjpeg($avatar);
					break;
					case 3:
						$image = imagecreatefrompng($avatar);
					break;
				}

				imagecopyresized($bg, $image, 5, 5, 0, 0, 50, 50, 50, 50);
			}
		}

		imageJPEG($bg, null, 100);
		imagedestroy($bg);
	}
}

$app = new app;

?>