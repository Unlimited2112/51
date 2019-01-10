using System;
using System.Windows.Forms;

namespace Hotel.Forms
{
    public partial class MoveForm : Form
    {
        int itemId = -1;
        public MoveForm(int itemId)
        {
            this.itemId = itemId;
            InitializeComponent();
        }

        private void MoveForm_Load(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.ActionsTableAdapter taAct = new HotelDataSetTableAdapters.ActionsTableAdapter();
            fldAction.DataSource = taAct.GetData();
            HotelDataSetTableAdapters.RoomsViewTableAdapter taRoom = new HotelDataSetTableAdapters.RoomsViewTableAdapter();
            fldRoom.DataSource = taRoom.GetData();
            HotelDataSetTableAdapters.MovesViewTableAdapter ta = new HotelDataSetTableAdapters.MovesViewTableAdapter();
            bindingItem.DataSource = ta.GetById(itemId);
        }

        private void btnOk_Click(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.MovesViewTableAdapter ta = new HotelDataSetTableAdapters.MovesViewTableAdapter();
            if (itemId < 0)
            {
                ta.InsertQuery(fldDates.Value, (int)fldAction.SelectedValue, (int)fldRoom.SelectedValue, fldDatesIn.Value, (int)fldDays.Value, (int)fldPersons.Value, Program.userId);
            }
            else
            {
                ta.UpdateQuery(fldDates.Value, (int)fldAction.SelectedValue, (int)fldRoom.SelectedValue, fldDatesIn.Value, (int)fldDays.Value, (int)fldPersons.Value, itemId);
            }
            Close();
        }

        private void btnCancel_Click(object sender, EventArgs e)
        {
            Close();
        }
    }
}
