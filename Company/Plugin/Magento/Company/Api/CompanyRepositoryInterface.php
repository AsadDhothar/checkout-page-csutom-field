<?php
declare(strict_types=1);

namespace Visionet\Company\Plugin\Magento\Company\Api;

class CompanyRepositoryInterface
{

    
    /**
     * @var \Magento\Company\Api\Data\CompanyExtensionFactory
     */
    protected $companyExtensionFactory;
    
    /**
     * @var \Magento\Company\Model\CompanyRepository
     */
    protected $companyRepository;

    /**
     * @param \Magento\Company\Model\CompanyRepository $companyRepository
     * @param \Magento\Company\Api\Data\CompanyExtensionFactory $companyExtensionFactory
     */
    public function __construct(
        \Magento\Company\Model\CompanyRepository $companyRepository,
        \Magento\Company\Api\Data\CompanyExtensionFactory $companyExtensionFactory
    ) {
        $this->companyRepository = $companyRepository;
        $this->companyExtensionFactory = $companyExtensionFactory;
    }

    /**
     * @param \Magento\Company\Api\CompanyRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGet(
        \Magento\Company\Api\CompanyRepositoryInterface $subject,
        $result
    ) {
        $company = $result;
        $extensionAttributes = $company->getExtensionAttributes();
        $companyExtension = $extensionAttributes ? $extensionAttributes : $this->companyExtensionFactory->create();
        
        $companyExtension->setKsaNumber($company->getData('ksa_number'));

        $company->setExtensionAttributes($companyExtension);
        return $company;
    }

    /**
     * @param \Magento\Company\Api\CompanyRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterSave(
        \Magento\Company\Api\CompanyRepositoryInterface $subject,
        $result
    ) {
        $company = $result;
        $extensionAttributes = $company->getExtensionAttributes();
        if (!$extensionAttributes) {
            return $company;
        }

        $ksaNumber = $extensionAttributes->getKsaNumber();
        if($ksaNumber === null){
            $ksaNumber = $company->getData('ksa_number');
        }
        $company->setData('ksa_number', $ksaNumber);

        $company->save();
        return $company;
    }

    /**
     * @param \Magento\Company\Api\CompanyRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGetList(
        \Magento\Company\Api\CompanyRepositoryInterface $subject,
        $result
    ) {
        foreach ($result->getItems() as $company) {
            $this->afterGet($subject, $company);
        }
        return $result;
    }
}

