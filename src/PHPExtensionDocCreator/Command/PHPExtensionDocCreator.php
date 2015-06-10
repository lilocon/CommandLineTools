<?php
namespace PHPExtensionDocCreator\Command;

use Console\Helper\QuestionHelper;
use PHPExtensionDocCreator\Generate\Generate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class PHPExtensionDocCreator
 * @package PHPExtensionDocCreator\Command
 * @author  Leo Yang <leoyang@motouch.cn>
 */
class PHPExtensionDocCreator extends Command
{

    protected function configure()
    {
        $this->setName('generate:phpext:doc')
            ->setDescription('生成PHP扩展的文档')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $extensionName = $questionHelper->askAndValid($output, $this->getExtensionNameQuestion());
        $output_dir = $questionHelper->askAndValid($output, $this->getOutputDirQuestion());

        $generate = new Generate();
        $generate->output($extensionName, $output_dir);

        return null;
    }


    /**
     * @return Question
     */
    protected function getExtensionNameQuestion()
    {
        $question = new Question('请指定扩展名称(<info>必填</info>): ');
        $question->setAutocompleterValues(get_loaded_extensions());

        $question->setValidator(function($val){
            if (!in_array($val, get_loaded_extensions())) {
                throw new \InvalidArgumentException("不存在的扩展");
            }
        });

        return $question;
    }

    /**
     * @return Question
     */
    protected function getOutputDirQuestion()
    {
        $question = new Question('请指定输出路径(<info>./</info>): ', './');
        return $question;
    }



}