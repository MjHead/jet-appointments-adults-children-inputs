# JetAppointmentsBooking. Allow Adults & Children fields in the appointment form

This plugin allows you to add seprate fields for Adults count and Children count and book total number of slots by the sum of values in these 2 fields.

Result example:

<img width="983" height="713" alt="image" src="https://github.com/user-attachments/assets/a8772f79-90cb-433d-bc51-ab96a8a5cb91" />

## How it works:

1. This plugin is JetAppointmentsBooking addon, so first of all you need JetAppointmentsBooking plugin installed.
2. Also it supports only JetFormBuilder (not JetEngine legacy forms), so also you need JetFormBuilder plugin installed.
3. Separate fields for adults and children means single time slot could be booked multiple times. So you need to enable **Manage Capacity** option of JetAppointmentsBooking plugin. Also you need option **User Can Manage Booked Capacity** to be disabled, because you'll manage capacity with adults and children fields.

<img width="735" height="329" alt="image" src="https://github.com/user-attachments/assets/0ee38b87-acd9-4c8d-8e74-4b348e2391c1" />

4. You need to add Adults and Children fields into the form manually. Optionally you can put them int the conditional block and show only user will select the slot in the calendar.
5. The last thing - you need to specify 4 properties to connect form and this addon - Adults field, Children field, Price per adult, Price per child. More detailed instructions on this below.

## Settings up the plugin properties

You can set up the plugin properies in the 2 ways
- Manually by changing default values here - https://github.com/MjHead/jet-appointments-adults-children-inputs/blob/main/jet-appointments-adults-children-inputs.php#L33-L40
- Or by setting up WordPress options - 'jet_apb_ac_adults_field', 'jet_apb_ac_children_field', 'jet_apb_ac_adults_price', 'jet_apb_ac_children_price'

To manage this with WordPress options you can create JetEngine Option Page. Here is the setup of the page

<img width="886" height="770" alt="image" src="https://github.com/user-attachments/assets/85276d0e-62bb-4309-bd3f-3242411124aa" />
<img width="879" height="475" alt="image" src="https://github.com/user-attachments/assets/891c3744-49b6-4f7d-a0c8-3e096fefc2cb" />

And the result

<img width="924" height="568" alt="image" src="https://github.com/user-attachments/assets/3571a711-591f-4a53-8211-c72145cc9a64" />

The important thing here are:
- **Slug of the page** must be exactly `jet_apb_ac`
- **Fields storage type** must be set to `Separate`
- **Add prefix for separate options** must be enabled
- Fields slugs must be exactly - `adults_field`, `children_field`, `adults_price`, `children_price`

## Price setup

Separate options for the price are needed to make this part compatible with WooCommerce integration of JetAppointmentsBooking and set correct price for the checkout. If you need to show total price in the form, you can use Calculated field and add these values directly into it, for example like this:

<img width="907" height="327" alt="image" src="https://github.com/user-attachments/assets/449a1771-fdfb-4599-b051-35dea2fae9f3" />

And the result

<img width="799" height="461" alt="image" src="https://github.com/user-attachments/assets/bcd8f976-281d-4e09-8983-3d52e46687ec" />







