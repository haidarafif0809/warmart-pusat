<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use SEOMeta;
use OpenGraph;
use Twitter;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        SEOMeta::setTitle('War-Mart.id');
        SEOMeta::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        SEOMeta::setCanonical('https://war-mart.id');
        SEOMeta::addKeyword(['warmart', 'warung', 'marketplace','toko online','belanja','lazada']);

        OpenGraph::setDescription('Warmart marketplace warung muslim pertama di Indonesia');
        OpenGraph::setTitle('War-Mart.id');
        OpenGraph::setUrl('https://war-mart.id');
        OpenGraph::addProperty('type', 'articles');

        return view('layouts.landing_page');
    }

    public function dashboard()
    {
        return view('home');
    }

    public function sms(){

        $client = new Client(); //GuzzleHttp\Client
        $result = $client->get('https://reguler.zenziva.net/apps/smsapi.php?userkey=k9d4p8&passkey=afifmaulana&nohp=081222498686&pesan=isi%20pesan');

        return $result->getBody(); 
    }
}
