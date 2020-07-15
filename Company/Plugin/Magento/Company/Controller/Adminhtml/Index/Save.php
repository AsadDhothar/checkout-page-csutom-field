<?php
declare(strict_types=1);

namespace Visionet\Company\Plugin\Magento\Company\Controller\Adminhtml\Index;

class Save
{

    /**
     * @param \Magento\Company\Controller\Adminhtml\Index\Save $subject
     * @param $result
     * @return mixed
     */
    public function afterSetCompanyRequestData(
        \Magento\Company\Controller\Adminhtml\Index\Save $subject,
        $result
    ) {
        $result->setData('ksa_number', $subject->getRequest()->getPostValue('general')['ksa_number']);

        return $result;
    }
}

