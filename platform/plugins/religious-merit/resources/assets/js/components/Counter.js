import React, { useState } from 'react';

export default function Counter() {
    // Set the initial count state to zero, 0
    const [count, setCount] = useState(0);

    // Create handleIncrement event handler
    const handleIncrement = () => {
        setCount((prevCount) => prevCount + 1);
    };

    // Create handleDecrement event handler
    const handleDecrement = () => {
        setCount((prevCount) => prevCount - 1);
    };
    return (
        <div>
            <button type="button" onClick={handleDecrement}>
                -
            </button>
            <span> {count} </span>
            <button type="button" onClick={handleIncrement}>
                +
            </button>
        </div>
    );
}
