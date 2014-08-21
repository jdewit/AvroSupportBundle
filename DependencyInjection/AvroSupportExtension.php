<?php
namespace Avro\SupportBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class AvroSupportExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load(sprintf('%s.xml', $config['db_driver']));

        if ($config['mailer_service'] == 'avro_support.mailer') {
            $loader->load($config['mailer_provider'] . '_mailer.xml');
        }

        $loader->load('answer.xml');
        $loader->load('category.xml');
        $loader->load('question.xml');

        if ($config['flashes_enabled'] == true) {
            $loader->load('flash.xml');
        }

        $container->setParameter('avro_support.from_email', $config['from_email']);
        $container->setParameter('avro_support.email_signature', $config['email_signature']);
        $container->setParameter('avro_support.min_role', $config['min_role']);
        $container->setParameter('avro_support.redirect_route', $config['redirect_route']);

        $container->setParameter('avro_support.question.class', $config['question']['class']);
        $container->setParameter('avro_support.question.allow_anon', $config['question']['allow_anon']);
        $container->setParameter('avro_support.question.form.type', $config['question']['form']['name']);

        $container->setParameter('avro_support.answer.class', $config['answer']['class']);
        $container->setParameter('avro_support.answer.form.type', $config['answer']['form']['name']);

        $container->setParameter('avro_support.category.class', $config['category']['class']);
        $container->setParameter('avro_support.category.form.type', $config['category']['form']['name']);

    }
}

