<%@ Page Title="Пользователи" Language="C#" MasterPageFile="~/Template.Master" AutoEventWireup="true" CodeBehind="Users.aspx.cs" Inherits="ForumSimple.Users" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <h3>Пользователи</h3>
    <asp:ListView ID="ListViewUsers" runat="server" DataKeyNames="id" 
        DataSourceID="dsUsers">
        <EmptyDataTemplate>
            <span>Нет данных...</span>
        </EmptyDataTemplate>
        <ItemTemplate>
            <div class="row rubric-list" />
                <div class="col-xs-6">
                    <asp:Label ID="loginLabel" runat="server" Text='<%# Eval("login") %>' />
                </div>
                <div class="col-xs-4">
                    <asp:Label ID="ruleNameLabel" runat="server" Text='<%# Eval("ruleName") %>' />
                </div>
                <div class="col-xs-2 text-right">
                    <asp:LinkButton ID="EditButton" runat="server" PostBackUrl='<%# "~/Profile.aspx?UserID=" + Eval("id") %>' CssClass="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></asp:LinkButton>
                </div>
            </div>
        </ItemTemplate>
        <LayoutTemplate>
            <div ID="itemPlaceholderContainer" runat="server" style="">
                <div runat="server" id="itemPlaceholder" class="row rubric-list" />
            </div>
            <div class="row rubric-list" />
                <asp:DataPager ID="DataPager1" runat="server">
                    <Fields>
                        <asp:NextPreviousPagerField ButtonType="Button" ShowFirstPageButton="True" ShowNextPageButton="False" ShowPreviousPageButton="False" ButtonCssClass="btn btn-default btn-sm" />
                        <asp:NumericPagerField />
                        <asp:NextPreviousPagerField ButtonType="Button" ShowLastPageButton="True" ShowNextPageButton="False" ShowPreviousPageButton="False" ButtonCssClass="btn btn-default btn-sm" />
                    </Fields>
                </asp:DataPager>
            </div>
        </LayoutTemplate>
    </asp:ListView>
    <asp:ObjectDataSource ID="dsUsers" runat="server" 
        OldValuesParameterFormatString="original_{0}" SelectMethod="GetData" 
        TypeName="ForumSimple.ForumDataSetTableAdapters.userViewTableAdapter" />
</asp:Content>
