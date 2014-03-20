<?php

namespace Kunstmaan\AdminBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

/**
 * The type for AnalyticsOverview
 */
class AnalyticsOverviewAdminType extends AbstractType
{

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('timespan');
        $builder->add('startOffset');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'analyticsoverview_form';
    }

}
