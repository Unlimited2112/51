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
    public partial class TypeForm : Form
    {
        int itemId = -1;
        public TypeForm(int itemId)
        {
            this.itemId = itemId;
            InitializeComponent();
        }

        private void TypeForm_Load(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.TypesTableAdapter ta = new HotelDataSetTableAdapters.TypesTableAdapter();
            bindingItem.DataSource = ta.GetById(itemId);
        }

        private void btnOk_Click(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.TypesTableAdapter ta = new HotelDataSetTableAdapters.TypesTableAdapter();
            if (itemId < 0)
            {
                ta.InsertQuery(fldCode.Text, fldName.Text);
            }
            else
            {
                ta.UpdateQuery(fldCode.Text, fldName.Text, itemId);
            }
            Close();
        }

        private void btnCancel_Click(object sender, EventArgs e)
        {
            Close();
        }
    }
}
