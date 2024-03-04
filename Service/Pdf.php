<?php
namespace Fontai\Bundle\PdfBundle\Service;

use GuzzleHttp\Client;


if (!function_exists('gzdecode'))
{
  function gzdecode($data) 
  { 
     return gzinflate(substr($data, 10, -8)); 
  } 
}

class Pdf
{
  public function generate($html, $options = [])
  {
    $client = new Client();
    $response = $client->request(
      'POST',
      'http://pdf.fontai.org',
      [
        'form_params' => [
          'html' => base64_encode(gzencode($html, 4)),
          'opts' => $options
        ],
        'timeout' => 120
      ]
    );

    if (
      ($data = @json_decode($response->getBody(), TRUE))
      && isset($data['data'])
    )
    {
      return gzdecode(base64_decode($data['data']));
    }

    return FALSE;
  }
}