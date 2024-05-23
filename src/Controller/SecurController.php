<?php

namespace App\Controller;

use App\Entity\Filiere;
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
use Symfony\Component\HttpKernel\Event\RequestEvent;
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
        $filieres = $em->getRepository(Filiere::class)->findAll(); 
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
            'form' => $userForm->createView(), 
            'filieres' => $filieres
        ]);
    }

    #[Route('/', name: 'login')]
public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $em): Response
{
    $filieres = $em->getRepository(Filiere::class)->findAll(); 

    // Vérifier si l'utilisateur est déjà connecté
    if ($this->getUser()) {
        // Rediriger l'utilisateur en fonction de son rôle
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_administration_admin');
        } elseif ($this->isGranted('ROLE_USER')) {
            // Vérifier si l'utilisateur a une validation égale à true
            $user = $this->getUser();
            if ($user->isValidation()) {
                return $this->redirectToRoute('app_administration_user');
            }
            else {
                $response = new Response();
    $response->setContent("
        <script>
            alert('Votre inscription est en attente de validation par un administrateur.');
            window.location.href ='/'; // Redirigez l'utilisateur vers une autre page si nécessaire
        </script>
    ");
    $response->headers->set('Content-Type', 'text/html');

    return $response;
                
            }
        }
    } else {
        // Obtenir les erreurs d'authentification précédentes
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

        // Afficher le formulaire de connexion avec les erreurs
        return $this->render('base.html.twig', [
            'error' => $error,
            'username' => $username, 
            'filieres' => $filieres
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
        $filieres = $em->getRepository(Filiere::class)->findAll(); 
        $user = $em->getRepository(User::class)->findBy(['validation' => false]);
        $user_validate = $em->getRepository(User::class)->findBy(['validation' => true]);
        return $this->render('/administration/gestion_user.html.twig', [
            'users' => $user,
            'user_validate' => $user_validate, 
            'filieres' =>$filieres
        ]);
    }
#[Route('administration/user/user/delete/{id}', name: 'users_delete', methods: ['POST'])]
public function delete(Request $request, EntityManagerInterface $em, User $user): Response
{
    $nom = $user->getLastname(); // Obtenez le nom de l'utilisateur
    $prenom = $user->getFirstname();
    $em->remove($user);
    $em->flush();
    $response = new Response();
    $response->setContent("
        <script>
            alert('Suppression de l'inscription de $prenom $nom du système.');
            window.location.href = '/administration/user/user'; // Redirigez l'utilisateur vers une autre page si nécessaire
        </script>
    ");
    $response->headers->set('Content-Type', 'text/html');

    return $response;
}

#[Route('administration/user/user/confirm/{id}', name: 'users_confirm', methods: ['POST'])]
public function confirm(Request $request, EntityManagerInterface $em, User $user): Response
{
    $nom = $user->getLastname(); // Obtenez le nom de l'utilisateur
    $prenom = $user->getFirstname();
    $user->setValidation(true);
    $em->flush();
    $response = new Response();
    $response->setContent("
        <script>
            alert('Confirmation de l'inscription de $prenom $nom avec succès dans le système.');
            window.location.href = '/administration/user/user'; // Redirigez l'utilisateur vers une autre page si nécessaire
        </script>
    ");
    $response->headers->set('Content-Type', 'text/html');

    return $response;
}

#[Route('administration/user/user/supprimer/{id}' , name : 'users_supprimer' , methods:['POST'])]
public function supprimer (Request $request , EntityManagerInterface $em , User $user) : Response
{
    $nom = $user->getLastname(); // Obtenez le nom de l'utilisateur
    $prenom = $user->getFirstname(); // Obtenez le prénom de l'utilisateur
    
    $em->remove($user);
    $em->flush();

    // Utilisez JavaScript pour afficher une alerte avec le nom et le prénom de l'utilisateur supprimé
    $response = new Response();
    $response->setContent("
        <script>
            alert('L\'utilisateur $prenom $nom a été supprimé avec succès du système.');
            window.location.href = '/administration/user/user'; // Redirigez l'utilisateur vers une autre page si nécessaire
        </script>
    ");
    $response->headers->set('Content-Type', 'text/html');

    return $response;
}
#[Route('/administration/user/user/modif/{id}', name: 'users_modif', methods: ['POST'])]
public function modification(Request $request, EntityManagerInterface $em, User $user): Response
{
        $nom = $user->getLastname();
        $prenom = $user->getFirstname();

        if ($user->getRoles()) {
            if (in_array('ROLE_USER', $user->getRoles())) {
                $user->setRoles(['ROLE_ADMIN']);
                $em->flush();
                $response = new Response();
                $response->setContent("
                    <script>
                        alert(\"$prenom $nom est maintenant devenue administrateur de l'application.\");
                        window.location.href = '/administration/user/user'; // Redirigez l'utilisateur vers une autre page si nécessaire
                    </script>
                ");
                $response->headers->set('Content-Type', 'text/html');

                return $response;
            } else {
                $user->setRoles(['ROLE_USER']);
                $em->flush();
                $response = new Response();
                $response->setContent("
                    <script>
                        alert(\"$prenom $nom est maintenant devenue utilisateur standard de l'application\");
                        window.location.href = '/administration/user/user'; // Redirigez l'utilisateur vers une autre page si nécessaire
                    </script>
                ");
                $response->headers->set('Content-Type', 'text/html');

                return $response;
            }
        }
    }


}
