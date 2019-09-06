<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\PlatformBundle\Acl;


use FOS\CommentBundle\Acl\RoleCommentAcl as BaseRoleCommentAcl;
use FOS\CommentBundle\Model\CommentInterface;
use FOS\CommentBundle\Model\SignedCommentInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Description of RoleCommentAcl
 *
 * @author franck
 */
class RoleCommentAcl extends BaseRoleCommentAcl
{
 /**
 *  
 * @var AuthorizationCheckerInterface
 */
private $authorizationChecker;

/**
 *
 * @var TokenStorage 
 */
private $tokenStorage;

/**
 * Constructor.
 *
 * @param AuthorizationCheckerInterface             $authorizationChecker
 * @param  TokenStorage            $tokenStorage
 * @param string                   $createRole
 * @param string                   $viewRole
 * @param string                   $editRole
 * @param string                   $deleteRole
 * @param string                   $commentClass
 */
public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorage $tokenStorage, $createRole, $viewRole, $editRole, $deleteRole, $commentClass
)
{
    parent::__construct(
            $authorizationChecker, $createRole, $viewRole, $editRole, $deleteRole, $commentClass);

    $this->authorizationChecker = $authorizationChecker;
    $this->tokenStorage = $tokenStorage;
}

/**
 * Checks if the Security token has an appropriate role to edit the supplied Comment.
 *
 * @param  CommentInterface $comment
 * @return boolean
 */
public function canEdit(CommentInterface $comment) {
    // the comment owner can edit the comment whenever he want.
    if ($comment instanceof SignedCommentInterface) {
        if ($comment->getAuthor() == $this->tokenStorage->getToken()->getUser()) {
            return true;
        }
    }
    //return parent::canEdit($comment);
}

/**
 * Checks if the Security token is allowed to delete a specific Comment.
 *
 * @param  CommentInterface $comment
 * @return boolean
 */
public function canDelete(CommentInterface $comment) {
     // the comment owner can delete the comment
    if ($comment instanceof SignedCommentInterface) {
        if ($comment->getAuthor() == $this->tokenStorage->getToken()->getUser()) {
            return true;
        }
    }
    //return parent::canDelete($comment);
}

/**
 * Checks if the Security token is allowed to reply to a parent comment.
 *
 * @param  CommentInterface|null $parent
 * @return boolean
 */
public function canReply(CommentInterface $parent = null) {

//    if ($parent instanceof SignedCommentInterface) {
//          //only the comment owner or the admin can reply to the comment.
//        if ($parent->getAuthor() == $this->tokenStorage->getToken()->getUser() ||
//                $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
//            return true;
//        }
//    }
//     if($parent !=null) {
//       // if the user have no access to reply then return false.
//          return false;
//     }  
   //this ligne allow all users to post new comments.
    return parent::canCreate();
}

public function canView(CommentInterface $comment) {
    
    return parent::canView($comment);
}
}