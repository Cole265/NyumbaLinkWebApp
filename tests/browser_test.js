// tests/browser_test.js
// Run this in browser console to test payment system

console.log('🧪 Khomolanu Payment System Test');
console.log('==================================\n');

// Test 1: Check for auth token
console.log('Test 1: Checking for auth token...');
const token = localStorage.getItem('auth_token') || localStorage.getItem('token') || sessionStorage.getItem('auth_token');
if (token) {
  console.log('✅ Auth token found:', token.substring(0, 20) + '...');
} else {
  console.warn('⚠️  No auth token found. You need to login first.');
}
console.log('');

// Test 2: Get user info
console.log('Test 2: Fetching user info...');
fetch('/api/v1/user', {
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
  }
})
.then(r => r.json())
.then(data => {
  if (data.id) {
    console.log('✅ User loaded:', {
      id: data.id,
      name: data.name,
      email: data.email,
      role: data.role,
    });
    if (data.role !== 'landlord') {
      console.warn('⚠️  User is NOT a landlord. Only landlords can publish properties.');
      console.warn('    Current role:', data.role);
    }
  } else {
    console.error('❌ Failed to load user:', data);
  }
  console.log('');
  
  // Test 3: Get properties
  console.log('Test 3: Fetching properties...');
  return fetch('/api/v1/properties?limit=5', {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    }
  });
})
.then(r => r.json())
.then(data => {
  if (data.data && data.data.length > 0) {
    console.log(`✅ Found ${data.data.length} properties:`);
    data.data.slice(0, 3).forEach(p => {
      console.log(`   - ID: ${p.id}, Title: ${p.title}, Type: ${p.property_type}`);
    });
    
    // Use first property for testing
    const propertyId = data.data[0].id;
    console.log('');
    
    // Test 4: Test payment endpoint
    console.log('Test 4: Testing payment endpoint...');
    return fetch('/api/v1/payments/listing-fee', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        property_id: propertyId,
        payment_method: 'mobile_money',
      })
    }).then(r => ({
      status: r.status,
      data: r.json(),
    })).then(async (r) => {
      const data = await r.data;
      console.log(`Response Status: ${r.status}`);
      console.log('Response Data:', data);
      
      if (r.status === 200 && data.success) {
        console.log('✅ Payment endpoint is working!');
        console.log('   Transaction ID:', data.transaction_id);
        console.log('   Payment ID:', data.payment_id);
      } else if (r.status === 403) {
        console.error('❌ Permission denied - Only landlords can publish properties');
      } else if (r.status === 401) {
        console.error('❌ Not authenticated');
      } else {
        console.error('❌ Payment endpoint error:', data.message);
      }
    });
  } else {
    console.error('❌ No properties found');
  }
})
.catch(error => {
  console.error('❌ Test error:', error);
  console.error('Make sure:');
  console.error('  1. Backend is running: php artisan serve');
  console.error('  2. You are logged in');
  console.error('  3. You are logged in as a LANDLORD');
});

console.log('\n📋 Test Summary:');
console.log('================');
console.log('This test checks:');
console.log('1. Auth token exists');
console.log('2. User can be fetched');
console.log('3. User has landlord role');
console.log('4. Properties exist');
console.log('5. Payment endpoint responds');
console.log('\n💡 Tip: Open console (F12) to see detailed output');
