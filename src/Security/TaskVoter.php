<?php


namespace App\Security;


use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    const DELETE = 'delete';
    const VALIDATE = 'validate';
    const EDIT = 'edit';

    private SessionInterface $session;

    private Security $security;

    public function __construct(SessionInterface $session, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE, self::VALIDATE, self::EDIT])) {
            return false;
        }

        // only vote on `Task` objects
        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Task object, thanks to `supports()`
        /** @var Task $task */
        $task = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($task, $user);
            case self::VALIDATE:
                return $this->canValidate($task, $user);
            case self::EDIT:
                return $this->canEdit($task, $user);

        }
        throw new \LogicException('Vous n\'avez pas accès à cette fonction');

    }

    private function canDelete(Task $task, User $user)
    {
        return $user === $task->getUser();
    }

    private function canValidate(Task $task, User $user)
    {
        return ($user === $task->getAssignedTo() || $this->security->isGranted('ROLE_ADMIN'));
    }

    private function canEdit(Task $task, User $user)
    {
        return ($user === $task->getAssignedTo() || $this->security->isGranted('ROLE_ADMIN') || $user === $task->getUser());
    }
}