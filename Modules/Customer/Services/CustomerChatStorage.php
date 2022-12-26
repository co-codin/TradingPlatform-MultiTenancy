<?php

declare(strict_types=1);

namespace Modules\Customer\Services;

use LogicException;
use Modules\Customer\Dto\CustomerChatMessageDto;
use Modules\Customer\Models\CustomerChatMessage;
use Modules\Customer\Models\Customer;

final class CustomerChatStorage
{
    /**
     * Store chat message.
     *
     * @param  Customer  $customer
     * @param  CustomerChatMessageDto $dto
     * @return CustomerChatMessage
     */
    public function store(Customer $customer, CustomerChatMessageDto $dto): CustomerChatMessage
    {
        $chatMessage = $dto->toArray();
        $chatMessage['user_id'] = $customer->conversion_user_id;
        $chatMessage['customer_id'] = $customer->id;
        $chatMessage['initiator_id'] = $customer->id;
        $chatMessage['initiator_type'] = 'customer';
        $chatMessage['read'] = 0;

        $message = CustomerChatMessage::create($chatMessage);

        if (!$message) {
            throw new LogicException(__('Can not create chat message'));
        }

        return $message;
    }
    /**
     * Update delivery.
     *
     * @param  int $customer_id
     * @return bool
     */
    public function updateDelivery(int $customer_id): bool
    {
        CustomerChatMessage::where('customer_id', $customer_id)->where('initiator_type', 'user')->where('read', 0)->update(['read' => 1]);

        return true;
    }
}
