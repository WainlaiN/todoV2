<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/admin")
 *
 * Class UserController
 *
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user_list")
     *
     * @param UserRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(UserRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        //$users = $userRepository->findAll();

        $users = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'user/index.html.twig',
            [
                'controller_name' => 'UserController',
                'users' => $users,
            ]
        );
    }

    /**
     * @Route("/users/create", name="user_create")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ResetPasswordHelperInterface $resetPasswordHelper
     * @param MailerInterface $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function createUser(
        Request $request,
        EntityManagerInterface $manager,
        ResetPasswordHelperInterface $resetPasswordHelper,
        MailerInterface $mailer
    ) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //generate default password for user
            $random = sha1(random_bytes(12));
            $user->setPassword($random);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté et l'email envoyé");

            //generate token and send email for new password
            $resetToken = $resetPasswordHelper->generateResetToken($user);
            $email = (new TemplatedEmail())
                ->from(new Address('nicodupblog@gmail.com', 'Todo List'))
                ->to($user->getEmail())
                ->subject('Generation de votre mot de passe')
                ->htmlTemplate('reset_password/generate_password.html.twig')
                ->context(
                    [
                        'resetToken' => $resetToken,
                        'tokenLifetime' => $resetPasswordHelper->getTokenLifetime(),
                    ]
                );

            $mailer->send($email);

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/users/{id}/edit", name="user_edit")
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editUser(
        User $user,
        Request $request,
        EntityManagerInterface $manager
    ) {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

}
