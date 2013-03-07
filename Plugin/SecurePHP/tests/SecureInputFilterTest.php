<?php


require_once "../libs/secure_input_filter.php";

/**
 * Unit test suite for the SecurePHP package
 *  test for SecureInputFilter class
 *
 * @author  Yasushi Ichikawa (@ichikaway)
 * @extends PHPUnit_TestCase
 */
class SecureInputFilterTest extends PHPUnit_Framework_TestCase
{

	public function testDeleteControlChar() {
		$input = array('a1' => "aa\x00bbb", 'b1' => "bbb\x01\x02\x03ccc", 'c1' => "ccc\x0addd");
		$expect = array('a1' => "aabbb", 'b1' => 'bbbccc', 'c1' => "ccc\x0addd");

		$result = SecureInputFilter::delete_cntrl_char($input);

		$this->assertEquals($expect, $result);
	}


	public function  testClean_key_value() {
		$input = array('a1' => "aa\x00bbb", 'b1' => "bbb\x01\x02\x03ccc", 'c1' => "ccc\x0addd");
		$expect = array('a1' => "aabbb", 'b1' => 'bbbccc', 'c1' => "ccc\x0addd");

		$result = SecureInputFilter::clean_key_value($input);
		$this->assertEquals($expect, $result);
	}


	public function  testClean_key_value_with_invalid_key() {
		$input = array(
			'a1' => "aa\x00bbb_ok", 'b1' => array('_SERVER'=>'delete', 'normal'=>'ok'), '_GET' => array('id'=>111),
			'a?e' => 'delete', 'no-o_k' => 'ok', 'onlyValue'
		);
		$expect = array('a1' => "aabbb_ok", 'b1' => array('normal'=>'ok'), 'no-o_k' => 'ok', 'onlyValue');

		$result = SecureInputFilter::clean_key_value($input);
		$this->assertEquals($expect, $result);
	}


	public function  testClean_key_value_with_Mongo_injection() {
		$input = array(
				'a1' => "aa\x00bbb_ok", '$in' => array('aa','bb'),
				);
		$expect = array('a1' => "aabbb_ok");

		$result = SecureInputFilter::clean_key_value($input);
		$this->assertEquals($expect, $result);
	}



}
?>