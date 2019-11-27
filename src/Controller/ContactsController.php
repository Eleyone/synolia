<?php
/**
 * Created by PhpStorm.
 * User: eleyo
 * Date: 27/11/2019
 * Time: 09:08
 */

namespace App\Controller;


use App\Adapter\SugarAPIAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactsController extends AbstractController
{
    /** @var SugarAPIAdapter $_sugarAPIAdapter */
    private $_sugarAPIAdapter;

    /**
     * ContactsController constructor.
     */
    public function __construct(SugarAPIAdapter $sugarAPIAdapter)
    {
        $this->_sugarAPIAdapter = $sugarAPIAdapter;
    }

    /**
     * @Route("/")
     */
    public function search()
    {
        $contacts = $this->_sugarAPIAdapter->serchContact();


        $content = "<html><body><table>";

        if (isset($contacts['records']) && is_array($contacts['records'])) {
            foreach ($contacts['records'] as $contact) {
                $urlDetailsContact = $this->generateUrl("contact_detail", ['contactId' => $contact['id']]);

                $addressString = $contact['primary_address_street'] . '<br/>'.
                    (($contact['primary_address_street_2']) ? $contact['primary_address_street_2'] . '<br/>' : '') .
                    (($contact['primary_address_street_3']) ? $contact['primary_address_street_3'] . '<br/>' : '') .
                    (($contact['primary_address_state']) ? $contact['primary_address_state'] . '<br/>' : '') .
                    $contact['primary_address_postalcode'] . ' ' . $contact['primary_address_city'] . '<br/>' .
                    $contact['primary_address_country'];

                $content .= "<tr>
                <td><a href='$urlDetailsContact'>{$contact['first_name']} {$contact['last_name']}</a></td>
                <td>{$contact['email'][0]['email_address']}</td>
                <td>
                    {$addressString}
                </td>
            </tr>";
            }
        } else {
            $content .= "No records found for contacts";
        }

        $content .= "</table></body></html>";


        return new Response($content);
    }

    /**
     * @Route("/ticket/{contactId}", name="contact_detail")
     * @param string $id
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function ticket(string $contactId)
    {
        $tickets = $this->_sugarAPIAdapter->getContactTickets($contactId);

        /*
        $content = "<html><body><table><thead><tr><td><a href=''>Create ticket</a></td></tr></thead><tbody>";


        foreach ($tickets as $ticket) {
            $content .= "<tr>
                    <td>{$ticket['subject']}</td>
                    <td>{$ticket['content']}</td>
                </tr>";
        }
        $content .= "</tbody></table></body></html>";

        return new Response($content);
        */

        return new Response(var_export($tickets, true));
    }

    /**
     * @Route("/help-api", name="api_help")
     *
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function help()
    {
        $content = "";

        try {
            $content = $this->_sugarAPIAdapter->help();
        } catch (\Throwable $e) {
            $content = "<html><body>{$e->getMessage()} ({$e->getFile()}:{$e->getLine()})<br>{$e->getTraceAsString()}</body></html>";
        } finally {
            return new Response($content);
        }
    }
}