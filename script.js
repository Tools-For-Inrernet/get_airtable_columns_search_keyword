// JavaScript code for populating dropdown and handling search form submission

// Function to populate the dropdown with column names
function populateDropdown(columns) {
  const columnDropdown = document.getElementById('column-dropdown');

  columns.forEach((column) => {
    const option = document.createElement('option');
    option.value = column;
    option.text = column;
    columnDropdown.appendChild(option);
  });
}

// Function to retrieve column names from Airtable
function getColumnNames() {
  // Replace 'YOUR_AIRTABLE_API_KEY' and 'YOUR_AIRTABLE_BASE_ID' with your actual Airtable API key and base ID
  const apiKey = 'YOUR_AIRTABLE_API_KEY';
  const baseId = 'YOUR_AIRTABLE_BASE_ID';
  const tableName = 'YOUR_TABLE_ID'; // Replace with your table name

  // Perform AJAX request to PHP endpoint to fetch column names
  fetch('get_column_names.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      apiKey: apiKey,
      baseId: baseId,
      tableName: tableName,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        populateDropdown(data.columnNames);
      } else {
        console.log('Error: Unable to fetch column names.');
      }
    })
    .catch((error) => {
      console.log('Error: ' + error);
    });
}

// Function to handle search form submission
function handleSearchFormSubmit(event) {
  event.preventDefault();

  const columnDropdown = document.getElementById('column-dropdown');
  const searchInput = document.getElementById('search-input');
  const resultsList = document.getElementById('results-list');

  const selectedColumn = columnDropdown.value;
  const searchText = searchInput.value;

  // Clear previous results
  resultsList.innerHTML = '';

  // Perform AJAX request to PHP endpoint passing selectedColumn and searchText as parameters
  fetch('search.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      selectedColumn: selectedColumn,
      searchText: searchText,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.length > 0) {
        data.forEach((result) => {
          const listItem = document.createElement('li');
          listItem.textContent = result;
          resultsList.appendChild(listItem);
        });
      } else {
        const noResultsItem = document.createElement('li');
        noResultsItem.textContent = 'No matching results found.';
        resultsList.appendChild(noResultsItem);
      }
    })
    .catch((error) => {
      console.log('Error: ' + error);
    });
}

// Event listener
document.addEventListener('DOMContentLoaded', () => {
  getColumnNames();

  // Attach event listener to the search form submission
  const searchForm = document.getElementById('search-form');
  searchForm.addEventListener('submit', handleSearchFormSubmit);
});
