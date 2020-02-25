# update the fees table

# make each fee type removable from the shopping cart
update fees set removable = 1;

# do not apply tax to any fee type
update fees set apply_tax = 0;
