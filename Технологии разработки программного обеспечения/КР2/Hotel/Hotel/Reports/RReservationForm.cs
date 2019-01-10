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
    public partial class RReservationForm : Form
    {
        public RReservationForm()
        {
            InitializeComponent();
        }

        private void RReservationForm_Load(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.ReportReservationTableAdapter ta = new HotelDataSetTableAdapters.ReportReservationTableAdapter();
            reportReservationBindingSource.DataSource = ta.GetData(DateTime.Now, DateTime.Now);
            reportViewer1.RefreshReport();
        }

        private void btnShow_Click(object sender, EventArgs e)
        {
            HotelDataSetTableAdapters.ReportReservationTableAdapter ta = new HotelDataSetTableAdapters.ReportReservationTableAdapter();
            reportReservationBindingSource.DataSource = ta.GetData(fldDateStart.Value, fldDateEnd.Value);
            reportViewer1.RefreshReport();
        }
    }
}
