using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Hotel.Forms
{
    public partial class UserForm : Form
    {
        int itemId = -1;
        public UserForm(int itemId)
        {
            this.itemId = itemId;
            InitializeComponent();
        }

        private void UserForm_Load(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.RolesTableAdapter taRole = new HotelDataSetTableAdapters.RolesTableAdapter();
            rolesBindingSource.DataSource = taRole.GetData();
            HotelDataSetTableAdapters.UsersViewTableAdapter ta = new HotelDataSetTableAdapters.UsersViewTableAdapter();
            bindingItem.DataSource = ta.GetById(itemId);
            if (itemId == Program.userId)
            {
                fldRole.Enabled = false;
            }
        }

        private void btnOk_Click(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.UsersViewTableAdapter ta = new HotelDataSetTableAdapters.UsersViewTableAdapter();
            if (itemId < 0)
            {
                ta.InsertQuery(fldName.Text, fldPassword.Text, (int)fldRole.SelectedValue);
            }
            else
            {
                ta.UpdateQuery(fldName.Text, fldPassword.Text, (int)fldRole.SelectedValue, itemId);
            }
            Close();
        }

        private void btnCancel_Click(object sender, EventArgs e)
        {
            Close();
        }
    }
}
