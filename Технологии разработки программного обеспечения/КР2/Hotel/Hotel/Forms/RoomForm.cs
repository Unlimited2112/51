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
    public partial class RoomForm : Form
    {
        int itemId = -1;
        public RoomForm(int itemId)
        {
            this.itemId = itemId;
            InitializeComponent();
        }

        private void RoomForm_Load(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.TypesTableAdapter taType = new HotelDataSetTableAdapters.TypesTableAdapter();
            typesBindingSource.DataSource = taType.GetData();
            HotelDataSetTableAdapters.RoomsViewTableAdapter ta = new HotelDataSetTableAdapters.RoomsViewTableAdapter();
            bindingItem.DataSource = ta.GetById(itemId);
        }

        private void btnOk_Click(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.RoomsViewTableAdapter ta = new HotelDataSetTableAdapters.RoomsViewTableAdapter();
            if (itemId < 0)
            {
                ta.InsertQuery((int)fldFloor.Value, (int)fldNumbers.Value, (int)fldType.SelectedValue, (int)fldPersons.Value);
            }
            else
            {
                ta.UpdateQuery((int)fldFloor.Value, (int)fldNumbers.Value, (int)fldType.SelectedValue, (int)fldPersons.Value, itemId);
            }
            Close();
        }

        private void btnCancel_Click(object sender, EventArgs e)
        {
            Close();
        }
    }
}
