# Phase 1

1. **Server Setup**  
   For the first phase, we had to set up the server that would be hosting the website. We chose the **LAMP** setup, which consists of:  
   - Linux operating system  
   - Apache  
   - MariaDB  
   - PHP  

2. **Installing Components**  
   The first step was to install Apache by running commands in bash. We could then install **MariaDB** and **phpMyAdmin** to manipulate data from the database using a GUI. Lastly, **PHP** needed to be installed, as this would be the scripting language used to connect to the database and display data on the client-side.  

3. **Development Environment**  
   After the LAMP setup was installed, we installed **Visual Studio Code** to have a proper environment for organizing files and code.  

4. **Server Testing and WiringPi Installation**  
   After testing the server by opening the Apache index page on localhost, we installed the **WiringPi PHP library** to access the GPIO pins of the Raspberry Pi using PHP.  

5. **Database Setup**  
   The Raspberry Pi setup was now complete, and we moved on to the database section.  
   - We created a database named **RFID**.  
   - We created a table named **Customer** with the following fields:  
     - `Customer_id` (integer)  
     - `name` (varchar)  
     - `Email` (varchar)  
     - `Password` (varchar)  

6. **Coding Phase**  
   - We created a simple registration form with text fields for **name**, **email**, and **password**.  
   - Using PHP, we validated the input and checked for duplicate accounts in the database.  
   - Notifications were added to display whether the registration process was successful.  
   - This step used **HTML, CSS, JavaScript, and PHP**.  

7. **Hardware Setup**  
   - We placed the **LEDs, resistors, buzzer, and wires**, connecting the breadboard (circuit) to the Raspberry Pi.  

8. **Controlling Hardware with PHP**  
   With the **WiringPi library**, we set up code to turn the LEDs and buzzer on and off by providing the GPIO pins and setting them as `1` or `0`. Example code:  

   ```php
   shell_exec("/usr/local/bin/gpio -g write 27 1");
   // this code turns on the LED
    ```

# Phase 2

1. **Initial Setup**  
   For this phase, the registration page was already completed, but the hardware was initially used only for testing and familiarization with the components.

2. **Database Update**  
   We added a second table to the database:  

   - **Temperature**  
     - `Temp_id` (integer)  
     - `Tem_threshold` (Decimal 5.2)  

3. **Front-End Setup**  
   - We created 2 tabs:  
     1. Registration page  
     2. Dashboard  

4. **Dashboard Gauges**  
   - The gauges were implemented using **ECharts JS**.  
   - The same gauge was used 4 times.  
   - Example reference: [Gauge Example](https://echarts.apache.org/examples/en/editor.html?c=gauge-temperature)  

5. **Fan Image Implementation**  
   - Added a fan image that rotates based on triggers.  
   - Rotation done using **CSS keyframes**.  
   - Two functions control:  
     1. Continuous rotation  
     2. Stop rotation  

6. **Threshold Inputs**  
   - Added inputs for the temperature thresholds of both fridges.  
   - Added **ON/OFF buttons** in the fan widget to manually control the fan.  

7. **Interface Elements**  
   - **Widgets**  
     - 4 widgets for:  
       - Title  
       - Temperature & Humidity for Fridge 1  
       - Temperature & Humidity for Fridge 2  
   - **Tabs**  
     - Registration page  
     - Dashboard page  
   - **Fan Widget**  
     - Title  
     - Fan image  
     - ON button  
     - OFF button  
   - **Threshold Controls**  
     - Text fields for threshold values  
     - Labels  
     - MODIFY buttons  

8. **Mosquitto Broker Installation**  

   ```bash
   sudo apt update
   # Press Y and Enter
   sudo apt install -y mosquitto mosquitto-clients
   sudo systemctl enable mosquitto.service
    ```

# Phase 3

1. **New Pages Created**  
   - Product/Inventory Management  
   - Self-Checkout System  
   - Login Page  

2. **Barcode and RFID Scanning**  
   - The checkout system reads barcodes using JavaScript:  
     ```javascript
     document.addEventListener('keydown', function (event) {
         // code to identify scanned product
     });
     ```  

3. **Product Identification**  
   - Scripts were created to identify scanned codes and match them with available inventory items.  

4. **Membership Validation**  
   - During payment, a script validates the customer’s membership code by comparing it with the database.  

5. **Payment Confirmation**  
   - Once the payment type is selected and confirmed:  
     - Loyalty points are added to the customer (if valid membership code)  
     - Receipt is sent via email  
     - Notification confirms the payment  

6. **Non-Membership Payments**  
   - If no valid membership code is provided:  
     - Loyalty points are not awarded  
     - Receipt is displayed on the screen  

7. **Inventory Interface**  
   - The inventory table displays current stock using **Bootstrap**  
   - Real-time updates ensure accurate stock data  

8. **Inventory Actions**  
   - **Modify Button**: Allows updating the EPC code of a product  
   - **Delete Button**: Removes the selected product  
   - **Add Product Button**: Enables adding new products with an EPC code  

9. **Product Table**  
   - Similar functionality to the inventory table, but products may not have EPC codes assigned  

10. **Client Management Page**  
    - Displays all registered customers in a table format  
    - Admin can delete customers from the database/permanent storage  

# Phase 4

## 1. Implemented Interfaces
The following interfaces have been implemented in Phase 4:

- **Sales reports**
- **Customer activity**
- **Product searching service**
- **Receipt history**
- **Sold quantity management**

## 2. Features
The following features have been added:

- **Dark and light mode**
- **Localization** (English and French)
- **Excel export sheet** using the [XLSX JS](https://www.npmjs.com/package/xlsx) (SheetJS, Spreadsheet, data parser and writer).  
  The script is integrated in the HTML page of the inventory interface.
