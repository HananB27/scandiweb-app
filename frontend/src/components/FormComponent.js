// OrderFormComponent.js
import React, { useState } from 'react';
import { gql, useMutation } from '@apollo/client';

// Define the GraphQL mutation for creating an order
const CREATE_ORDER_MUTATION = gql`
  mutation CreateOrder($customerName: String!, $productIds: [String!]!) {
    createOrder(customer_name: $customerName, product_ids: $productIds) {
      id
      customer_name
      order_date
      products {
        id
        name
        description
        price {
          amount
          currency_label
          currency_symbol
        }
      }
    }
  }
`;

const OrderFormComponent = () => {
  const [customerName, setCustomerName] = useState('');
  const [productIds, setProductIds] = useState(''); // This will take input like "ps-5, apple-imac-2021"
  const [response, setResponse] = useState(null);

  // Use Apollo's useMutation hook to execute the create order mutation
  const [createOrder, { loading, error }] = useMutation(CREATE_ORDER_MUTATION, {
    onCompleted: (data) => {
      setResponse(data.createOrder);
    },
  });

  // Handle form submission
  const handleSubmit = (e) => {
    e.preventDefault();

    // Convert the comma-separated productIds input into an array
    const productIdsArray = productIds.split(',').map((id) => id.trim());

    // Execute the mutation
    createOrder({
      variables: {
        customerName,
        productIds: productIdsArray,
      },
    }).catch((err) => {
      console.error("Error creating order:", err);
    });
  };

  return (
    <div>
      <h2>Create New Order</h2>
      <form onSubmit={handleSubmit}>
        <div>
          <label>Customer Name:</label>
          <input
            type="text"
            value={customerName}
            onChange={(e) => setCustomerName(e.target.value)}
            required
          />
        </div>
        <div>
          <label>Product IDs (comma separated):</label>
          <input
            type="text"
            value={productIds}
            onChange={(e) => setProductIds(e.target.value)}
            placeholder="e.g., ps-5, apple-imac-2021"
            required
          />
        </div>
        <button type="submit" disabled={loading}>
          {loading ? 'Submitting...' : 'Submit Order'}
        </button>
      </form>

      {error && (
        <div style={{ color: 'red' }}>
          <p>Error creating order: {error.message}</p>
        </div>
      )}

      {response && (
        <div>
          <h3>Order Created Successfully</h3>
          <p>Order ID: {response.id}</p>
          <p>Customer Name: {response.customer_name}</p>
          <p>Order Date: {response.order_date}</p>
          <h4>Products:</h4>
          <ul>
            {response.products.map((product) => (
              <li key={product.id}>
                <p>Name: {product.name}</p>
                <p>Description: {product.description}</p>
                <p>
                  Price: {product.price.amount} {product.price.currency_symbol}
                </p>
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
};

export default OrderFormComponent;
