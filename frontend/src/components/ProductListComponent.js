import React, { useEffect, useState } from 'react';

const ProductListComponent = () => {
  const [items, setItems] = useState([]);
  const [graphqlErrors, setGraphQLErrors] = useState([]);

  useEffect(() => {
    const fetchGraphQLData = async () => {
      try {
        const res = await fetch('https://scandiweb-app-production.up.railway.app/graphql', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            query: `{
              products {
                id
                name
                description
                inStock
                brand
                category {
                  id
                  name
                }
                price {
                  amount
                  currency {
                    label
                    symbol
                  }
                }
                attributes {
                  name
                  items {
                    displayValue
                    value
                  }
                }
                product_galleries {
                  image_url
                }
              }
            }`,
          }),
        });
        
        const text = await res.text();
        
        // Log the raw response to identify issues
        console.log("Server response:", text);
        
        // Attempt to parse as JSON, or handle as an error if it's not JSON
        try {
          const result = JSON.parse(text);
          
          if (result.errors) {
            setGraphQLErrors(result.errors);
          }
    
          if (res.ok && result.data) {
            setItems(result.data.products);
          }
        } catch (jsonError) {
          console.error("Failed to parse JSON response:", jsonError);
          console.error("Response text:", text);
          setGraphQLErrors([{ message: "Invalid response format from server." }]);
        }
    
      } catch (error) {
        console.error('Error fetching GraphQL data:', error);
      }
    };
    
    
    fetchGraphQLData();
  }, []);

  return (
    <div>
      <h2>Product List</h2>
      {graphqlErrors.length > 0 && (
        <div>
          <h3>GraphQL Errors</h3>
          <ul>
            {graphqlErrors.map((error, index) => (
              <li key={index}>{error.message}</li>
            ))}
          </ul>
        </div>
      )}
      <ul>
        {items?.map((item) => (
          <li key={item?.id}>
            <h3>{item?.name}</h3>
            <p>{item?.description}</p>
            <p>Category: {item?.category?.name}</p>
            <p>Brand: {item?.brand}</p>
            <p>In Stock: {item?.inStock}</p>
            <p>
              Attributes: 
              {item?.attributes?.map((attr, index) => (
                <span key={index}>
                  {attr?.name}: 
                  {attr?.items?.map((item, idx) => (
                    <span key={idx}>{item?.displayValue} ({item?.value})</span>
                  )).reduce((prev, curr) => [prev, ', ', curr]) || 'N/A'}
                </span>
              )) || 'N/A'}
            </p>
            <p>
              Gallery: 
              {item?.product_galleries?.map((image, index) => (
                <img key={index} src={image?.image_url} alt={`Gallery image ${index}`} style={{ width: '100px', height: '100px' }} />
              )) || 'No images available'}
            </p>
            <p>
              Price: {item?.price?.amount} {item?.price?.currency?.symbol} ({item?.price?.currency?.label})
            </p>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default ProductListComponent;
