import React, { useState } from 'react';
import FormComponent from './components/FormComponent';
import ProductListComponent from './components/ProductListComponent';

const App = () => {
  const [orders, setOrders] = useState([]);

  return (
    <div>      
      <h1>Order Management</h1>
      <FormComponent setOrders={setOrders} />
      <h2>Existing Orders</h2>
      <ul>
        {orders.map((order) => (
          <li key={order.id}>
            <p>Order ID: {order.id}</p>
            <p>Customer: {order.customer_name}</p>
            <p>Date: {order.order_date}</p>
          </li>
        ))}
      </ul>
      <ProductListComponent />
    </div>
  );
};

export default App;
