<?php

namespace App\Controller;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact')]
class ContactController extends AbstractController
{

    #[Route('/', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $contact = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to('rezgui.m.w40@gmail.com')
                ->subject('contact depuis le site Instahome')
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'mail'=> $contact->get("email")->getData(),
                    'sujet'=> $contact->get('sujet')->getData(),
                    'message'=> $contact->get('message')->getData(),
                ])
            ;

            $mailer->send($email);
            $this->addFlash('message', 'Mail de contact envoyé');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);    }

}