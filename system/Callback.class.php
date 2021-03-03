<?php
namespace Rosance 
{
	class Callback
	{
		/**
		 * Send success sweetalert callback
		 * @param string $message
		 */
		public function SendSuccessOnMainPage( $message)
		{
			echo "<script type='text/javascript'> showsuccess('".$message."', true) </script>";
		}
		/**
		 * Send success sweetalert callback then redirect the user to a diferent page
		 * @param string $message
		 * @param string $link
		 * Link can be relative or absolute
		 */
		public function SendSuccessOnMainPageWithRedirect( $message,  $link)
		{
			echo "<script type='text/javascript'> showsuccess('".$message."',false,'".$link."') </script>";
		}
		/**
		 * Send success sweetalert callback
		 * @param string $message
		 * @param string $link
		 * Link can be relative or absolute
		 */
		public function SendSuccessLogoutOnMainPage( $message,  $link)
		{
			echo "<script type='text/javascript'> showsuccesslogout('".$message."','".$link."') </script>";
		}
		/**
		 * Send error sweetalert callback
		 * @param string $message
		 */
		public function SendErrorOnMainPage( $message)
		{
			echo "<script type='text/javascript'> showerror('".$message."') </script>";
		}
		/**
		 * Send success toast callback
		 * @param string $message
		 * Link can be relative or absolute
		 */
		public function SendSuccessToast( $message)
		{
			echo "<script type='text/javascript'>toast('".$message."')</script>";
		}
		/**
		 * Send error toast callback
		 * @param string $message
		 */
		public function SendErrorToast( $message)
		{
			echo "<script type='text/javascript'>etoast('".$message."')</script>";
		}
	}
}
?>