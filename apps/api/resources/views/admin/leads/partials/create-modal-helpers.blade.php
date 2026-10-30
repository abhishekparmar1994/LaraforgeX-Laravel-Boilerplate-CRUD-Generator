<script>
  // Pincode Auto-Fill Engine & Sizing Estimator
  const INDIA_PINCODES = {
    '390001': { city: 'Vadodara', state: 'Gujarat' }, '390007': { city: 'Vadodara', state: 'Gujarat' },
    '390011': { city: 'Vadodara', state: 'Gujarat' }, '390015': { city: 'Vadodara', state: 'Gujarat' },
    '390020': { city: 'Vadodara', state: 'Gujarat' }, '395001': { city: 'Surat', state: 'Gujarat' },
    '395007': { city: 'Surat', state: 'Gujarat' },    '380001': { city: 'Ahmedabad', state: 'Gujarat' },
    '380015': { city: 'Ahmedabad', state: 'Gujarat' },'360001': { city: 'Rajkot', state: 'Gujarat' },
    '364001': { city: 'Bhavnagar', state: 'Gujarat' },'388001': { city: 'Anand', state: 'Gujarat' },
    '392001': { city: 'Bharuch', state: 'Gujarat' },  '400001': { city: 'Mumbai', state: 'Maharashtra' },
    '411001': { city: 'Pune', state: 'Maharashtra' },  '110001': { city: 'New Delhi', state: 'Delhi' },
    '474012': { city: 'Gwalior', state: 'Madhya Pradesh' }
  };

  /**
   * Purpose: Auto-populate city & state based on standard Indian pincode.
   * Parameters: string pin
   */
  function handlePincodeAutoFill(pin) {
    const cleanPin = pin.trim();
    if (cleanPin.length === 6 && INDIA_PINCODES[cleanPin]) {
      document.getElementById('lead-city').value = INDIA_PINCODES[cleanPin].city;
      document.getElementById('lead-state').value = INDIA_PINCODES[cleanPin].state;
    }
  }

  /**
   * Purpose: Estimate solar load (kW) and budget based on monthly electric bill.
   * Parameters: number|string amount
   */
  function handleBillChange(amount) {
    const bill = parseFloat(amount) || 0;
    if (bill > 0) {
      const estimatedKw = Math.max(1, Math.round((bill / 850) * 2) / 2);
      document.getElementById('lead-sanctioned-load').value = estimatedKw;
      document.getElementById('lead-deal-size').value = estimatedKw * 55000;
    }
  }
</script>
