<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Security\Model\Authenticator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class SecurController extends AbstractController
{
    private $authenticator;

    public function __construct(AuthenticatorInterface $authenticator)
    {
        $this->authenticator = $authenticator;
    }
    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $Passwordhasher): Response
    {
        $admin1 = $em->getRepository(User::class)->findOneBy(['lastname' => 'BARRY', 'firstname' => 'Boubacar']);
        $admin2 = $em->getRepository(User::class)->findOneBy(['lastname' => 'KHTEIRA', 'firstname' => 'Habib']);

        if (!$admin1) {
            $email = 'bouba@demo.fr';
            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$existingUser) {
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($Passwordhasher->hashPassword($user, 'Bouba2345#'));
                $user->setFirstname('BARRY');
                $user->setLastname('Boubacar');
                $user->setRoles(["ROLE_ADMIN"]);
                $user->setValidation(true);
                $em->persist($user);
                $em->flush();
            }
        } else {
            $admin1->setRoles(["ROLE_ADMIN"]);
        }

        if (!$admin2) {
            $email = 'habib@demo.fr';
            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$existingUser) {
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($Passwordhasher->hashPassword($user, 'Habib2345#'));
                $user->setFirstname('KHTEIRA');
                $user->setLastname('Habib');
                $user->setRoles(["ROLE_ADMIN"]);
                $user->setValidation(true);
                $em->persist($user);
                $em->flush();
            }
        } else {
            $admin2->setRoles(["ROLE_ADMIN"]);
        }

        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword($Passwordhasher->hashPassword($user, $user->getPassword()));
            $user->setRoles(['ROLE_USER']);
            $user->setValidation(false);
            $em->persist($user);
            $em->flush();
            $this->addFlash('succès', 'Bienvenue sur notre home');
            return $this->redirectToRoute('login');
        }
        return $this->render('secur/signup.html.twig', [
            'form' => $userForm->createView()
        ]);
    }

    #[Route('/', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_administration_admin');
            } elseif ($this->isGranted('ROLE_USER')) {
                return $this->redirectToRoute('app_administration_user');
            }
        } else {
            $error = $authenticationUtils->getLastAuthenticationError();
            $username = $authenticationUtils->getLastUsername();
            return $this->render('base.html.twig', [
                'error' => $error,
                'username' => $username
            ]);
        }
    }

    #[Route('/logout', name: 'logout')]

    public function logout()
    {
    }

    #[Route('/administration/user/user', name: 'app_admin_user')]
    public function gestionUser(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $user = $em->getRepository(User::class)->findBy(['validation' => false]);
        $user_validate = $em->getRepository(User::class)->findBy(['validation' => true]);
        return $this->render('/administration/gestion_user.html.twig', [
            'users' => $user,
            'user_validate' => $user_validate
        ]);
    }
    #[Route('administration/user/user/{userFirstname}/delete', name: 'users_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, User $user): JsonResponse
    {
        dd($request);
        $em->remove($user);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }

    #[Route('administration/user/user/{userFirstname}/confirm', name: 'users_confirm', methods: ['POST'])]
    public function confirm(Request $request, EntityManagerInterface $em, User $user): JsonResponse
    {
        $user->setValidation(true);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
}
