<?php
	/**
	*
	* 	@package com.Itschi.base.plugins.HTTP
	* 	@since 2013/02/06
	*
	*	DO NOT MODIFY ANY OF THESE FUNCTIONS.
	*	These functions are essential for the use of plugins.
	*	Editing may cause your cat to be eaten by your subwoofer
	*	or serious frozen air around your head.
	*	It may also cause headaches.
	*	Dafuq did I just read?
	*/

	$_root = dirname(__FILE__).DIRECTORY_SEPARATOR.'HTTP'.DIRECTORY_SEPARATOR;

	/*
		+-----------------+
		| load interfaces |
		+-----------------+
	*/
	require_once $_root.'interfaces'.DIRECTORY_SEPARATOR.'HTTP.interface.php';
	require_once $_root.'interfaces'.DIRECTORY_SEPARATOR.'HTTPRequest.interface.php';
	require_once $_root.'interfaces'.DIRECTORY_SEPARATOR.'HTTPRequestData.interface.php';
	require_once $_root.'interfaces'.DIRECTORY_SEPARATOR.'HTTPResponse.interface.php';

	/*
		+--------------+
		| load classes |
		+--------------+
	*/
	require_once $_root.'HTTP.class.php';
	require_once $_root.'HTTPRequest.class.php';
	require_once $_root.'HTTPRequestData.class.php';
	require_once $_root.'HTTPResponse.class.php';
?>
