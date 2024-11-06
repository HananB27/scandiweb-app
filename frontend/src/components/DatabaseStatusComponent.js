import React, { useEffect, useState } from 'react';

const DatabaseStatusComponent = () => {
  const [dbStatus, setDbStatus] = useState(null);

  useEffect(() => {
    const fetchDBStatus = async () => {
      try {
        const res = await fetch('http://localhost:8000/db_test.php');
        if (!res.ok) {
          throw new Error('Network response was not ok');
        }
        const data = await res.json();
        setDbStatus(data);
      } catch (error) {
        console.error('Error fetching database status:', error);
        setDbStatus({ status: 'error', message: 'Failed to connect to the backend.' });
      }
    };

    fetchDBStatus();
  }, []);

  return (
    <div>
      <h1>Database Connection Status</h1>
      {dbStatus ? <p>{dbStatus.message}</p> : <p>Loading...</p>}
    </div>
  );
};

export default DatabaseStatusComponent;
