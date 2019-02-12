<?php

namespace App\Form;

use App\Entity\CommentEntity;
use App\Entity\CommentTypeEntity;
use App\Entity\ContentEntity;
use App\Repository\CommentTypeEntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentEntityType extends AbstractType
{
    private $commentTypeEntityRepo;
    public function __construct(CommentTypeEntityRepository $commentTypeEntityRepo)
    {
        $this->commentTypeEntityRepo = $commentTypeEntityRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentBody',TextareaType::class,array(
                "label" => "评论内容:",
            ))
            ->add("commentTypeEntity", ChoiceType::class, array(
                "choices" => array(
                    $this->commentTypeEntityRepo->findAll()
                ),
                "choice_label" => function($commentTypeEntity, $key, $value){
                    /** @var CommentTypeEntity $commentTypeEntity */
                    return $commentTypeEntity->getCommentTypeName();
                },
                "group_by" => function(){

                },
                "label" => "评论过滤:",
                "help" => "基本html标签:<a href hreflang> <em> <strong> <cite> <blockquote cite> <code> <ul type> <ol start type> <li> <dl> <dt> <dd> <h2 id> <h3 id> <h4 id> <h5 id> <h6 id>"
            ))
            //评论entity表单,还得要增加: 用户, contentEntity字段,此两个字段在,前台页面手动添加吧.
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentEntity::class,
        ]);
    }
}
