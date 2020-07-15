<?php

namespace Visionet\Checkout\Plugin\Magento\Sales\Api;

class OrderRepositoryInterface
{


    /**
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory
     */
    protected $OrderExtensionFactory;

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * @param \Magento\Sales\Model\OrderRepository $orderRepository
     * @param \Magento\Sales\Api\Data\OrderExtensionFactory $OrderExtensionFactory
     */
    public function __construct(
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Sales\Api\Data\OrderExtensionFactory $OrderExtensionFactory
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderExtensionFactory = $OrderExtensionFactory;
    }

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGet(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        $result
    ) {
        $order = $result;
        $extensionAttributes = $order->getExtensionAttributes();
        $orderExtension = $extensionAttributes ? $extensionAttributes : $this->orderExtensionFactory->create();

        $order->getData('comment');
        $order->setExtensionAttributes($orderExtension);
        return $order;
    }

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterSave(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        $result
    ) {
        $order = $result;
        $extensionAttributes = $order->getExtensionAttributes();
        if (!$extensionAttributes) {
            return $order;
        }

        $comment = $order->getData('comment');
        $order->setData('comment', $comment);

        $order->save();
        return $order;
    }

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param $result
     * @return mixed
     */
    public function afterGetList(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        $result
    ) {
        foreach ($result->getItems() as $order) {
            $this->afterGet($subject, $order);
        }
        return $result;
    }
}

