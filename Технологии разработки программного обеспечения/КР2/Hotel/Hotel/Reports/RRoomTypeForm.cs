using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Hotel.Reports
{
    public partial class RRoomTypeForm : Form
    {
        public RRoomTypeForm()
        {
            InitializeComponent();
        }

        private void RRoomTypeForm_Load(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.ReportRoomTypeTableAdapter ta = new HotelDataSetTableAdapters.ReportRoomTypeTableAdapter();
            reportRoomTypeBindingSource.DataSource = ta.GetData();
            reportViewer1.RefreshReport();
        }
    }
}
