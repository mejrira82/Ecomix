<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator,
        EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt
    ): Response {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            // Create header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // Create payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // Generate token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // Send mail
            $mail->send(
                'no-reply@ecomix.tn',
                $user->getEmail(),
                'Activate your account',
                'register',
                compact('user', 'token')
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) {
            $payload = $jwt->getPayload($token);
            $user = $usersRepository->find($payload['user_id']);

            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Activated user');
                return $this->redirectToRoute('account_index');
            }
        }
        $this->addFlash('danger', 'The token is invalid or has expired');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/resendverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'You must be logged in to access this page');
            return $this->redirectToRoute('app_login');
        }
        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'This user is already activated');
            return $this->redirectToRoute('account_index');
        }
        // Create header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // Create payload
        $payload = [
            'user_id' => $user->getId()
        ];

        // Generate token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // Send mail
        $mail->send(
            'no-reply@ecomix.tn',
            $user->getEmail(),
            'Activate your account',
            'register',
            compact('user', 'token')
        );
        $this->addFlash('success', 'Email sent successfully ');
        return $this->redirectToRoute('account_index');
    }
}